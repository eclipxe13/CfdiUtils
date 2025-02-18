<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Emisor;
use PHPUnit\Framework\TestCase;

final class EmisorTest extends TestCase
{
    /** @var Emisor */
    public $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new Emisor();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('cfdi:Emisor', $this->element->getElementName());
    }
}
