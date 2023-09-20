<?php

declare(strict_types=1);

namespace Tests\PhpOffice\Math\Writer;

use PhpOffice\Math\Element;
use PhpOffice\Math\Math;
use PhpOffice\Math\Writer\OfficeMathML;

class OfficeMathMLTest extends WriterTestCase 
{
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

        $writer = new OfficeMathML();
        $output = $writer->write($math);
        
        $expected = '<m:oMathPara xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math">'
            . '<m:oMath>'
            . '<m:f>'
            . '<m:num><m:r><m:t>π</m:t></m:r></m:num>'
            . '<m:den><m:r><m:t>2</m:t></m:r></m:den>'
            . '</m:f>'
            . '</m:oMath>'
            . '</m:oMathPara>';
        $this->assertEquals($expected, $output);
    }
}