<?php

namespace CfdiUtilsTests\Elements\Dividendos10;

use CfdiUtils\Elements\Dividendos10\Remanente;
use PHPUnit\Framework\TestCase;

class RemanenteTest extends TestCase
{
    /** @var Remanente */
    public $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new Remanente();
    }

    public function testGetElementName()
    {
        $this->assertSame('dividendos:Remanente', $this->element->getElementName());
    }
}
