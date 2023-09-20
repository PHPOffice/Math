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
                return new Element\Identifier($nodeElement->nodeValue);
            case 'mn':
                return new Element\Numeric($nodeElement->nodeValue);
            case 'mo':
                return new Element\Operator($nodeElement->nodeValue);
            case 'mrow':
                return new Element\Row();
            case 'msup':
                return new Element\Superscript();
            default: 
                throw new Exception(sprintf(
                    '%s : The tag `%s` is not implemented',
                    __METHOD__, 
                    $nodeElement->nodeName
                ));
        }
    }
}