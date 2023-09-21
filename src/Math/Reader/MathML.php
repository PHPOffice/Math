<?php

namespace PhpOffice\Math\Reader;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use PhpOffice\Math\Math;
use PhpOffice\Math\Element;

class MathML implements ReaderInterface
{
    /** @var Math */
    private $math;

    /** @var DOMDocument */
    private $dom;

    /** @var DOMXpath */
    private $xpath;

    public function read(string $content): ?Math
    {
        $content = str_replace(
            [
                '&InvisibleTimes;',
            ],
            [
                '<mchar name="InvisibleTimes"/>',
            ],
            $content
        );

        $this->dom = new DOMDocument();
        $this->dom->loadXML($content, LIBXML_DTDLOAD);

        $this->math = new Math();
        $this->parseNode(null, $this->math);

        return $this->math;
    }

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
        $nodeValue = trim($nodeElement->nodeValue);
        switch ($nodeElement->nodeName) {
            case 'mfrac':
                $element = new Element\Fraction();
                $nodeList = $this->xpath->query('*', $nodeElement);
                if ($nodeList->count() == 2) {
                    $element
                        ->setNumerator($this->getElement($nodeList->item(0)))
                        ->setDenominator($this->getElement($nodeList->item(1)));
                }
                return $element;
            case 'mi':
                return new Element\Identifier($nodeValue);
            case 'mn':
                return new Element\Numeric($nodeValue);
            case 'mo':
                if (empty($nodeValue)) {
                    $nodeList = $this->xpath->query('*', $nodeElement);
                    if (
                        $nodeList->count() == 1 
                        && $nodeList->item(0)->nodeName == 'mchar'
                        && $nodeList->item(0)->hasAttribute('name')
                    ) {
                        $nodeValue = $nodeList->item(0)->getAttribute('name');
                    }
                }
                return new Element\Operator($nodeValue);
            case 'mrow':
                return new Element\Row();
            case 'msup':
                $element = new Element\Superscript();
                $nodeList = $this->xpath->query('*', $nodeElement);
                if ($nodeList->count() == 2) {
                    $element
                        ->setBase($this->getElement($nodeList->item(0)))
                        ->setSuperscript($this->getElement($nodeList->item(1)));
                }
                return $element;
            default: 
                throw new Exception(sprintf(
                    '%s : The tag `%s` is not implemented',
                    __METHOD__, 
                    $nodeElement->nodeName
                ));
        }
    }
}