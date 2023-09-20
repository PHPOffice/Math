<?php

declare(strict_types=1);

namespace Tests\PhpOffice\Math\Reader;

use PHPUnit\Framework\TestCase;
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
        self::assertInstanceOf(Math::class, $reader->read($content));
    }
}