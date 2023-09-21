<?php

declare(strict_types=1);

namespace Tests\PhpOffice\Math\Reader;

use PHPUnit\Framework\TestCase;
use PhpOffice\Math\Element;
use PhpOffice\Math\Math;
use PhpOffice\Math\Reader\OfficeMathML;

class OfficeMathMLTest extends TestCase 
{
    /**
     * @covers OfficeMathML::read
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

        $subElements = $elements[0]->getElements();
        $this->assertCount(1, $subElements);
        $this->assertInstanceOf(Element\Fraction::class, $subElements[0]);

        $this->assertInstanceOf(Element\Identifier::class, $subElements[0]->getNumerator());
        $this->assertEquals('π', $subElements[0]->getNumerator()->getValue());

        $this->assertInstanceOf(Element\Numeric::class, $subElements[0]->getDenominator());
        $this->assertEquals(2, $subElements[0]->getDenominator()->getValue());
    }


    /**
     * @covers OfficeMathML::read
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

      $this->assertInstanceOf(Element\Fraction::class, $elements[0]);
      $this->assertInstanceOf(Element\Identifier::class, $elements[0]->getNumerator());
      $this->assertEquals('π', $elements[0]->getNumerator()->getValue());
      $this->assertInstanceOf(Element\Numeric::class, $elements[0]->getDenominator());
      $this->assertEquals(2, $elements[0]->getDenominator()->getValue());

      $this->assertInstanceOf(Element\Operator::class, $elements[1]);
      $this->assertEquals('+', $elements[1]->getValue());

      $this->assertInstanceOf(Element\Identifier::class, $elements[2]);
      $this->assertEquals('a', $elements[2]->getValue());

      $this->assertInstanceOf(Element\Operator::class, $elements[3]);
      $this->assertEquals('∗', $elements[3]->getValue());

      $this->assertInstanceOf(Element\Numeric::class, $elements[4]);
      $this->assertEquals(2, $elements[4]->getValue());
    }
}