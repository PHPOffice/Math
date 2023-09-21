<?php

namespace PhpOffice\Math\Reader;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use PhpOffice\Math\Math;
use PhpOffice\Math\Element;

class OfficeMathML implements ReaderInterface
{
    /** @var Math */
    protected $math;

    /** @var XMLReader */
    protected $xmlReader;

    /** @var DOMXpath */
    protected $xpath;

    /** @var string[] */
    protected $operators = ['+', '-', '/', '∗'];

    public function read(string $content): ?Math
    {
        $nsMath = 'xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"';
        $nsWord = 'xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"';

        $content = str_replace(
            $nsMath,
            $nsMath . ' ' . $nsWord,
            $content
        );

        $this->dom = new DOMDocument();
        $this->dom->loadXML($content);

        $this->math = new Math();
        $this->parseNode(null, $this->math);

        return $this->math;
    }

    /**
     * @link https://devblogs.microsoft.com/math-in-office/officemath/
     * @link https://learn.microsoft.com/fr-fr/archive/blogs/murrays/mathml-and-ecma-math-omml
     */
    protected function parseNode(?DOMElement $nodeRowElement, $parent): void
    {
        $this->xpath = new DOMXpath($this->dom);
        foreach ($this->xpath->query('*', $nodeRowElement) as $nodeElement) {
            $element = $this->getElement($nodeElement);
            $parent->add($element);

            if ($element instanceof Element\AbstractGroupElement) {
                $this->parseNode($nodeElement, $element);
            }
        }
    }

    protected function getElement(DOMElement $nodeElement): Element\AbstractElement
    {
        switch ($nodeElement->nodeName) {
            case 'm:f':
                $element = new Element\Fraction();
                // Numerator
                $nodeNumerator = $this->xpath->query('m:num/m:r/m:t', $nodeElement);
                if ($nodeNumerator->length == 1) {
                    $value = $nodeNumerator->item(0)->nodeValue;
                    if (is_numeric($value)) {
                        $element->setNumerator(new Element\Numeric($value));
                    } else {
                        $element->setNumerator(new Element\Identifier($value));
                    }
                }
                // Denominator
                $nodeDenominator= $this->xpath->query('m:den/m:r/m:t', $nodeElement);
                if ($nodeDenominator->length == 1) {
                    $value = $nodeDenominator->item(0)->nodeValue;
                    if (is_numeric($value)) {
                        $element->setDenominator(new Element\Numeric($value));
                    } else {
                        $element->setDenominator(new Element\Identifier($value));
                    }
                }
                return $element;
            case 'm:r':
                $nodeText = $this->xpath->query('m:t', $nodeElement);
                if ($nodeText->length == 1) {
                    $value = trim($nodeText->item(0)->nodeValue);
                    if (in_array($value, $this->operators)) {
                        return new Element\Operator($value);
                    }
                    if (is_numeric($value)) {
                        return new Element\Numeric($value);
                    }
                    return new Element\Identifier($value);
                }
                break;
            case 'm:oMath':
                return new Element\Row();
            default: 
                throw new Exception(sprintf(
                    '%s : The tag `%s` is not implemented',
                    __METHOD__, 
                    $nodeElement->nodeName
                ));
        }
    }
}