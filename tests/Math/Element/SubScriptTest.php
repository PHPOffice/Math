<?php

declare(strict_types=1);

namespace Tests\PhpOffice\Math\Element;

use PhpOffice\Math\Element;
use PhpOffice\Math\Element\Subscript;
use PHPUnit\Framework\TestCase;

class SubScriptTest extends TestCase
{
    public function testConstruct(): void
    {
        $subScript = new Subscript(
            new Element\Identifier('O'),
            new Element\Numeric(2)
        );

        $this->assertInstanceOf(
            Element\Identifier::class,
            $subScript->getBase()
        );

        $this->assertInstanceOf(
            Element\Numeric::class,
            $subScript->getSubscript()
        );
    }

    public function testBase(): void
    {
        $identifierO = new Element\Identifier('O');
        $identifierC = new Element\Identifier('C');
        $identifierN = new Element\Identifier('N');

        $subScript = new Subscript($identifierO, $identifierC);

        $this->assertEquals($identifierO, $subScript->getBase());

        $this->assertInstanceOf(
            SubScript::class,
            $subScript->setBase($identifierN)
        );

        $this->assertEquals($identifierN, $subScript->getBase());
    }

    public function testSubscript(): void
    {
        $identifierO = new Element\Identifier('O');
        $identifier2 = new Element\Identifier('2');
        $identifier3 = new Element\Identifier('3');

        $subScript = new Subscript($identifierO, $identifier2);

        $this->assertEquals(
            $identifier2,
            $subScript->getSubscript()
        );

        $this->assertInstanceOf(
            SubScript::class,
            $subScript->setSubscript($identifier3)
        );

        $this->assertEquals(
            $identifier3,
            $subScript->getSubscript()
        );
    }
}
