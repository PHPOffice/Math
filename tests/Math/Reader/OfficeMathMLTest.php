<?php

declare(strict_types=1);

namespace Tests\PhpOffice\Math\Reader;

use PhpOffice\Math\Element;
use PhpOffice\Math\Math;
use PhpOffice\Math\Reader\OfficeMathML;
use PHPUnit\Framework\TestCase;

class OfficeMathMLTest extends TestCase
{
    /**
     * @covers \OfficeMathML::read
     */
    public function testRead(): void
    {
        $content = '<m:oMathPara xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math">
        <m:oMath>
          <m:f>
            <m:num><m:r><m:t>π</m:t></m:r></m:num>
            <m:den><m:r><m:t>2</m:t></m:r></m:den>
          </m:f>
        </m:oMath>
      </m:oMathPara>';

        $reader = new OfficeMathML();
        $math = $reader->read($content);
        $this->assertInstanceOf(Math::class, $math);

        $elements = $math->getElements();
        $this->assertCount(1, $elements);
        $this->assertInstanceOf(Element\Row::class, $elements[0]);

        /** @var Element\Row $element */
        $element = $elements[0];
        $subElements = $element->getElements();
        $this->assertCount(1, $subElements);
        $this->assertInstanceOf(Element\Fraction::class, $subElements[0]);

        /** @var Element\Fraction $subElement */
        $subElement = $subElements[0];

        /** @var Element\Identifier $numerator */
        $numerator = $subElement->getNumerator();
        $this->assertInstanceOf(Element\Identifier::class, $numerator);
        $this->assertEquals('π', $numerator->getValue());

        /** @var Element\Numeric $denominator */
        $denominator = $subElement->getDenominator();
        $this->assertInstanceOf(Element\Numeric::class, $denominator);
        $this->assertEquals(2, $denominator->getValue());
    }

    /**
     * @covers \OfficeMathML::read
     */
    public function testReadWithWTag(): void
    {
        $content = '<m:oMath xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math">
          <m:f>
            <m:num>
              <m:r>
                <w:rPr><w:rFonts w:ascii="Cambria Math" w:hAnsi="Cambria Math"/></w:rPr>
                <m:t xml:space="preserve">π</m:t>
              </m:r>
            </m:num>
            <m:den>
              <m:r>
                <w:rPr><w:rFonts w:ascii="Cambria Math" w:hAnsi="Cambria Math"/></w:rPr>
                <m:t xml:space="preserve">2</m:t>
              </m:r>
            </m:den>
          </m:f>
          <m:r>
            <w:rPr><w:rFonts w:ascii="Cambria Math" w:hAnsi="Cambria Math"/></w:rPr>
            <m:t xml:space="preserve">+</m:t>
          </m:r>
          <m:r>
            <w:rPr><w:rFonts w:ascii="Cambria Math" w:hAnsi="Cambria Math"/></w:rPr>
            <m:t xml:space="preserve">a</m:t>
          </m:r>
          <m:r>
            <w:rPr><w:rFonts w:ascii="Cambria Math" w:hAnsi="Cambria Math"/></w:rPr>
            <m:t xml:space="preserve">∗</m:t>
          </m:r>
          <m:r>
            <w:rPr><w:rFonts w:ascii="Cambria Math" w:hAnsi="Cambria Math"/></w:rPr>
            <m:t xml:space="preserve">2</m:t>
          </m:r>
        </m:oMath>';

        $reader = new OfficeMathML();
        $math = $reader->read($content);
        $this->assertInstanceOf(Math::class, $math);

        $elements = $math->getElements();
        $this->assertCount(5, $elements);

        /** @var Element\Fraction $element */
        $element = $elements[0];
        $this->assertInstanceOf(Element\Fraction::class, $element);
        /** @var Element\Identifier $numerator */
        $numerator = $element->getNumerator();
        $this->assertInstanceOf(Element\Identifier::class, $numerator);
        $this->assertEquals('π', $numerator->getValue());
        /** @var Element\Numeric $denominator */
        $denominator = $element->getDenominator();
        $this->assertInstanceOf(Element\Numeric::class, $denominator);
        $this->assertEquals(2, $denominator->getValue());

        /** @var Element\Operator $element */
        $element = $elements[1];
        $this->assertInstanceOf(Element\Operator::class, $element);
        $this->assertEquals('+', $element->getValue());

        /** @var Element\Identifier $element */
        $element = $elements[2];
        $this->assertInstanceOf(Element\Identifier::class, $element);
        $this->assertEquals('a', $element->getValue());

        /** @var Element\Operator $element */
        $element = $elements[3];
        $this->assertInstanceOf(Element\Operator::class, $element);
        $this->assertEquals('∗', $element->getValue());

        /** @var Element\Numeric $element */
        $element = $elements[4];
        $this->assertInstanceOf(Element\Numeric::class, $element);
        $this->assertEquals(2, $element->getValue());
    }
}
