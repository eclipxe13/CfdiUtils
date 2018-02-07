<?php
namespace CfdiUtilsTests\Elements\Pagos10;

use CfdiUtils\Elements\Pagos10\Impuestos;
use CfdiUtils\Elements\Pagos10\Retenciones;
use CfdiUtils\Elements\Pagos10\Traslados;
use PHPUnit\Framework\TestCase;

class ImpuestosTest extends TestCase
{
    /** @var Impuestos */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Impuestos();
    }

    public function testConstructedObject()
    {
        $this->assertSame('pagos10:Impuestos', $this->element->getElementName());
    }

    public function testGetTraslados()
    {
        $this->assertNull($this->element->searchNode('pagos10:Traslados'));
        $child = $this->element->getTraslados();
        $this->assertInstanceOf(Traslados::class, $child);
        $this->assertSame($child, $this->element->searchNode('pagos10:Traslados'));
    }

    public function testGetRetenciones()
    {
        $this->assertNull($this->element->searchNode('pagos10:Retenciones'));
        $child = $this->element->getRetenciones();
        $this->assertInstanceOf(Retenciones::class, $child);
        $this->assertSame($child, $this->element->searchNode('pagos10:Retenciones'));
    }
}
