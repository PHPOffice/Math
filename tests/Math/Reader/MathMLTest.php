<?php

declare(strict_types=1);

namespace Tests\PhpOffice\Math\Reader;

use PHPUnit\Framework\TestCase;
use PhpOffice\Math\Math;
use PhpOffice\Math\Reader\MathML;

class MathMLTest extends TestCase 
{
    /**
     * @covers MathML::read
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
        self::assertInstanceOf(Math::class, $reader->read($content));
    }

    /**
     * @covers MathML::read
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
        self::assertInstanceOf(Math::class, $reader->read($content));
    }
}