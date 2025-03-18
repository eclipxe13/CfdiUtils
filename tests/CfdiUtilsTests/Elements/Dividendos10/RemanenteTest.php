<?php

namespace CfdiUtilsTests\Elements\Dividendos10;

use CfdiUtils\Elements\Dividendos10\Remanente;
use PHPUnit\Framework\TestCase;

final class RemanenteTest extends TestCase
{
    public Remanente $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new Remanente();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('dividendos:Remanente', $this->element->getElementName());
    }
}
