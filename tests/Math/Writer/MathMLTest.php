<?php

declare(strict_types=1);

namespace Tests\PhpOffice\Math\Writer;

use PhpOffice\Math\Element;
use PhpOffice\Math\Math;
use PhpOffice\Math\Writer\MathML;

class MathMLTest extends WriterTestCase 
{
    /**
     * @covers MathML::write
     */
    public function testWrite(): void
    {
        $opTimes = new Element\Operator('&InvisibleTimes;');

        $math = new Math();

        $row = new Element\Row();
        $math->add($row);

        $row->add(new Element\Identifier('a'));
        $row->add(clone $opTimes);

        $superscript = new Element\Superscript();
        $superscript->setBase(new Element\Identifier('x'));
        $superscript->setSuperscript(new Element\Numeric(2));
        $row->add($superscript);

        $row->add(new Element\Operator('+'));
        
        $row->add(new Element\Identifier('b'));
        $row->add(clone $opTimes);
        $row->add(new Element\Identifier('x'));
        
        $row->add(new Element\Operator('+'));

        $row->add(new Element\Identifier('c'));

        $writer = new MathML();
        $output = $writer->write($math);
        
        $expected = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<!DOCTYPE math PUBLIC "-//W3C//DTD MathML 2.0//EN" "http://www.w3.org/Math/DTD/mathml2/mathml2.dtd">'
            . '<math xmlns="http://www.w3.org/1998/Math/MathML">'
            . '<mrow><mi>a</mi><mo>&amp;InvisibleTimes;</mo><msup><mi>x</mi><mn>2</mn></msup><mo>+</mo><mi>b</mi><mo>&amp;InvisibleTimes;</mo><mi>x</mi><mo>+</mo><mi>c</mi>'
            . '</mrow>'
            . '</math>'
            . PHP_EOL;
        $this->assertEquals($expected, $output);
        $this->assertIsSchemaMathMLValid($output);
    }
    /**
     * @covers OfficeMathML::write
     */
    public function testWriteFraction(): void
    {
        $math = new Math();

        $fraction = new Element\Fraction();
        $fraction
            ->setDenominator(new Element\Numeric(2))
            ->setNumerator(new Element\Identifier('π'))
        ;
        $math->add($fraction);

        $writer = new MathML();
        $output = $writer->write($math);
        
        $expected = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<!DOCTYPE math PUBLIC "-//W3C//DTD MathML 2.0//EN" "http://www.w3.org/Math/DTD/mathml2/mathml2.dtd">'
            . '<math xmlns="http://www.w3.org/1998/Math/MathML">'
            . '<mfrac>'
            . '<mi>π</mi><mn>2</mn>'
            . '</mfrac>'
            . '</math>'
            . PHP_EOL;
        $this->assertEquals($expected, $output);
        $this->assertIsSchemaMathMLValid($output);
    }
}