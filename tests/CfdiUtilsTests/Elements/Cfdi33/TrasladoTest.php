<?php
namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Traslado;
use PHPUnit\Framework\TestCase;

class TrasladoTest extends TestCase
{
    /** @var Traslado */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Traslado();
    }

    public function testGetElementName()
    {
        $this->assertSame('cfdi:Traslado', $this->element->getElementName());
    }
}
