<?php

declare(strict_types=1);

namespace Tests\PhpOffice\Math\Reader;

use PhpOffice\Math\Element;
use PhpOffice\Math\Math;
use PhpOffice\Math\Reader\MathML;
use PHPUnit\Framework\TestCase;

class MathMLTest extends TestCase
{
    /**
     * @covers \MathML::read
     */
    public function testReadBasic(): void
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>
        <!DOCTYPE math PUBLIC "-//W3C//DTD MathML 2.0//EN" "http://www.w3.org/Math/DTD/mathml2/mathml2.dtd">
        <math xmlns="http://www.w3.org/1998/Math/MathML">
            <mrow>
                <mi>a</mi> <mo>&InvisibleTimes;</mo> <msup><mi>x</mi><mn>2</mn></msup>
                <mo>+</mo><mi>b</mi><mo>&InvisibleTimes;</mo><mi>x</mi>
                <mo>+</mo><mi>c</mi>
            </mrow>
        </math>';

        $reader = new MathML();
        $math = $reader->read($content);
        $this->assertInstanceOf(Math::class, $math);

        $elements = $math->getElements();
        $this->assertCount(1, $elements);
        $this->assertInstanceOf(Element\Row::class, $elements[0]);

        /** @var Element\Row $element */
        $element = $elements[0];
        $subElements = $element->getElements();
        $this->assertCount(9, $subElements);

        /** @var Element\Identifier $subElement */
        $subElement = $subElements[0];
        $this->assertInstanceOf(Element\Identifier::class, $subElement);
        $this->assertEquals('a', $subElement->getValue());

        /** @var Element\Identifier $subElement */
        $subElement = $subElements[1];
        $this->assertInstanceOf(Element\Operator::class, $subElement);
        $this->assertEquals('InvisibleTimes', $subElement->getValue());

        /** @var Element\Superscript $subElement */
        $subElement = $subElements[2];
        $this->assertInstanceOf(Element\Superscript::class, $subElements[2]);

        /** @var Element\Identifier $base */
        $base = $subElement->getBase();
        $this->assertInstanceOf(Element\Identifier::class, $base);
        $this->assertEquals('x', $base->getValue());

        /** @var Element\Numeric $superscript */
        $superscript = $subElement->getSuperscript();
        $this->assertInstanceOf(Element\Numeric::class, $superscript);
        $this->assertEquals(2, $superscript->getValue());

        /** @var Element\Operator $subElement */
        $subElement = $subElements[3];
        $this->assertInstanceOf(Element\Operator::class, $subElement);
        $this->assertEquals('+', $subElement->getValue());

        /** @var Element\Identifier $subElement */
        $subElement = $subElements[4];
        $this->assertInstanceOf(Element\Identifier::class, $subElement);
        $this->assertEquals('b', $subElement->getValue());

        /** @var Element\Operator $subElement */
        $subElement = $subElements[5];
        $this->assertInstanceOf(Element\Operator::class, $subElement);
        $this->assertEquals('InvisibleTimes', $subElement->getValue());

        /** @var Element\Identifier $subElement */
        $subElement = $subElements[6];
        $this->assertInstanceOf(Element\Identifier::class, $subElement);
        $this->assertEquals('x', $subElement->getValue());

        /** @var Element\Operator $subElement */
        $subElement = $subElements[7];
        $this->assertInstanceOf(Element\Operator::class, $subElement);
        $this->assertEquals('+', $subElement->getValue());

        /** @var Element\Identifier $subElement */
        $subElement = $subElements[8];
        $this->assertInstanceOf(Element\Identifier::class, $subElement);
        $this->assertEquals('c', $subElement->getValue());
    }

    /**
     * @covers \MathML::read
     */
    public function testReadFraction(): void
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>
        <!DOCTYPE math PUBLIC "-//W3C//DTD MathML 2.0//EN" "http://www.w3.org/Math/DTD/mathml2/mathml2.dtd">
        <math xmlns="http://www.w3.org/1998/Math/MathML">
            <mfrac bevelled="true">
                <mfrac>
                    <mi> a </mi>
                    <mi> b </mi>
                </mfrac>
                <mfrac>
                    <mi> c </mi>
                    <mi> d </mi>
                </mfrac>
            </mfrac>
        </math>';

        $reader = new MathML();
        $math = $reader->read($content);
        $this->assertInstanceOf(Math::class, $math);

        $elements = $math->getElements();
        $this->assertCount(1, $elements);
        $this->assertInstanceOf(Element\Fraction::class, $elements[0]);

        /** @var Element\Fraction $element */
        $element = $elements[0];

        $this->assertInstanceOf(Element\Fraction::class, $element->getNumerator());
        /** @var Element\Fraction $subElement */
        $subElement = $element->getNumerator();

        /** @var Element\Identifier $numerator */
        $numerator = $subElement->getNumerator();
        $this->assertInstanceOf(Element\Identifier::class, $numerator);
        $this->assertEquals('a', $numerator->getValue());
        /** @var Element\Identifier $denominator */
        $denominator = $subElement->getDenominator();
        $this->assertInstanceOf(Element\Identifier::class, $denominator);
        $this->assertEquals('b', $denominator->getValue());

        $this->assertInstanceOf(Element\Fraction::class, $element->getDenominator());
        /** @var Element\Fraction $subElement */
        $subElement = $element->getDenominator();

        /** @var Element\Identifier $numerator */
        $numerator = $subElement->getNumerator();
        $this->assertInstanceOf(Element\Identifier::class, $numerator);
        $this->assertEquals('c', $numerator->getValue());
        /** @var Element\Identifier $denominator */
        $denominator = $subElement->getDenominator();
        $this->assertInstanceOf(Element\Identifier::class, $denominator);
        $this->assertEquals('d', $denominator->getValue());
    }
}
