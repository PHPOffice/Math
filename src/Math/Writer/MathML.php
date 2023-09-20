<?php

namespace PhpOffice\Math\Writer;

use Exception;
use PhpOffice\Math\Math;
use PhpOffice\Math\Element;
use XMLWriter;

class MathML implements WriterInterface
{
    /** @var XMLWriter */
    private $output;

    /**
     * @param Math $math
     * @return string
     */
    public function write(Math $math): string
    {
        $this->output = new XMLWriter();
        $this->output->openMemory();
        $this->output->startDocument('1.0', 'UTF-8');
        $this->output->writeDtd('math', '-//W3C//DTD MathML 2.0//EN', 'http://www.w3.org/Math/DTD/mathml2/mathml2.dtd');
        $this->output->startElement('math');
        $this->output->writeAttribute('xmlns', 'http://www.w3.org/1998/Math/MathML');

        foreach ($math->getElements() as $element) {
            $this->writeElementItem($element);
        }

        $this->output->endElement();
        $this->output->endDocument();

        return $this->output->outputMemory();
    }

    protected function writeElementItem(Element\AbstractElement $element): void
    {
        // Element\AbstractGroupElement
        if ($element instanceof Element\AbstractGroupElement) {
            $this->output->startElement($this->getElementTagName($element));
            foreach ($element->getElements() as $childElement) {
                $this->writeElementItem($childElement);
            }
            $this->output->endElement();
            return;
        }

        // Element\Fraction
        if ($element instanceof Element\Fraction) {
            $this->output->startElement($this->getElementTagName($element));
            $this->writeElementItem($element->getNumerator());
            $this->writeElementItem($element->getDenominator());
            $this->output->endElement();
            return;
        }

        // Element\AbstractElement
        $this->output->startElement($this->getElementTagName($element));
        $this->output->text((string) $element->getContent());
        $this->output->endElement();
    }

    protected function getElementTagName(Element\AbstractElement $element): string
    {
        // Group
        if ($element instanceof Element\Row) {
            return 'mrow';
        }
        if ($element instanceof Element\Superscript) {
            return 'msup';
        }
        if ($element instanceof Element\AbstractGroupElement) {
            throw new Exception(sprintf(
                '%s : The element of the class `%s` has no tag name',
                __METHOD__, 
                get_class($element)
            ));
        }

        //
        if ($element instanceof Element\Fraction) {
            return 'mfrac';
        }
        if ($element instanceof Element\Identifier) {
            return 'mi';
        }
        if ($element instanceof Element\Numeric) {
            return 'mn';
        }
        if ($element instanceof Element\Operator) {
            return 'mo';
        }
        
        throw new Exception(sprintf(
            '%s : The element of the class `%s` has no tag name',
            __METHOD__, 
            get_class($element)
        ));
    }
}