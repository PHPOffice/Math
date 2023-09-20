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
    private $math;

    /** @var XMLReader */
    private $xmlReader;

    /** @var DOMXpath */
    private $xpath;

    public function read(string $content): ?Math
    {
        $this->dom = new DOMDocument();
        $this->dom->loadXML($content);

        $this->math = new Math();
        $this->parseNode(null, $this->math);

        return $this->math;
    }

    /**
     * @link https://devblogs.microsoft.com/math-in-office/officemath/
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
                if ($nodeNumerator->count() == 1) {
                    $value = $nodeNumerator->item(0)->nodeValue;
                    if (is_numeric($value)) {
                        $element->setNumerator(new Element\Numeric($value));
                    } else {
                        $element->setNumerator(new Element\Identifier($value));
                    }
                }
                // Denominator
                $nodeDenominator= $this->xpath->query('m:den/m:r/m:t', $nodeElement);
                if ($nodeDenominator->count() == 1) {
                    $value = $nodeDenominator->item(0)->nodeValue;
                    if (is_numeric($value)) {
                        $element->setDenominator(new Element\Numeric($value));
                    } else {
                        $element->setDenominator(new Element\Identifier($value));
                    }
                }
                return $element;
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