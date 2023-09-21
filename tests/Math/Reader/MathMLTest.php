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

        $element = $elements[0];
        $subElements = $element->getElements();
        $this->assertCount(9, $subElements);

        $this->assertInstanceOf(Element\Identifier::class, $subElements[0]);
        $this->assertEquals('a', $subElements[0]->getValue());

        $this->assertInstanceOf(Element\Operator::class, $subElements[1]);
        $this->assertEquals('InvisibleTimes', $subElements[1]->getValue());

        $this->assertInstanceOf(Element\Superscript::class, $subElements[2]);
        $this->assertInstanceOf(Element\Identifier::class, $subElements[2]->getBase());
        $this->assertEquals('x', $subElements[2]->getBase()->getValue());
        $this->assertInstanceOf(Element\Numeric::class, $subElements[2]->getSuperscript());
        $this->assertEquals(2, $subElements[2]->getSuperscript()->getValue());

        $this->assertInstanceOf(Element\Operator::class, $subElements[3]);
        $this->assertEquals('+', $subElements[3]->getValue());

        $this->assertInstanceOf(Element\Identifier::class, $subElements[4]);
        $this->assertEquals('b', $subElements[4]->getValue());

        $this->assertInstanceOf(Element\Operator::class, $subElements[5]);
        $this->assertEquals('InvisibleTimes', $subElements[5]->getValue());

        $this->assertInstanceOf(Element\Identifier::class, $subElements[6]);
        $this->assertEquals('x', $subElements[6]->getValue());

        $this->assertInstanceOf(Element\Operator::class, $subElements[7]);
        $this->assertEquals('+', $subElements[7]->getValue());

        $this->assertInstanceOf(Element\Identifier::class, $subElements[8]);
        $this->assertEquals('c', $subElements[8]->getValue());
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

        $element = $elements[0];

        $this->assertInstanceOf(Element\Fraction::class, $element->getNumerator());
        $subElement = $element->getNumerator();
        $this->assertInstanceOf(Element\Identifier::class, $subElement->getNumerator());
        $this->assertEquals('a', $subElement->getNumerator()->getValue());
        $this->assertInstanceOf(Element\Identifier::class, $subElement->getDenominator());
        $this->assertEquals('b', $subElement->getDenominator()->getValue());

        $this->assertInstanceOf(Element\Fraction::class, $element->getDenominator());
        $subElement = $element->getDenominator();
        $this->assertInstanceOf(Element\Identifier::class, $subElement->getNumerator());
        $this->assertEquals('c', $subElement->getNumerator()->getValue());
        $this->assertInstanceOf(Element\Identifier::class, $subElement->getDenominator());
        $this->assertEquals('d', $subElement->getDenominator()->getValue());
    }
}
