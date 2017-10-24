<?php
namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Concepto;
use CfdiUtils\Elements\Cfdi33\CuentaPredial;
use CfdiUtils\Elements\Cfdi33\Impuestos;
use CfdiUtils\Elements\Cfdi33\Retencion;
use CfdiUtils\Elements\Cfdi33\Traslado;
use PHPUnit\Framework\TestCase;

class ConceptoTest extends TestCase
{
    /** @var  Concepto */
    public $element;

    public function setUp()
    {
        parent::setUp();
        $this->element = new Concepto();
    }
    public function testGetImpuestos()
    {
        $this->assertNull($this->element->searchNode('cfdi:Impuestos'));
        $child = $this->element->getImpuestos();
        $this->assertInstanceOf(Impuestos::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:Impuestos'));
    }

    public function testAddTraslado()
    {
        $this->assertNull($this->element->searchNode('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado'));
        $first = $this->element->addTraslado(['name' => 'first']);
        $this->assertInstanceOf(Traslado::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertSame($first, $this->element->searchNode('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado'));
    }

    public function testMultiTraslado()
    {
        $parent = $this->element->getImpuestos()->getTraslados();
        $this->assertCount(0, $parent);
        $this->element->multiTraslado(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $parent);
        $this->assertSame('first', $parent->searchAttribute('cfdi:Traslado', 'id'));
    }

    public function testAddRetencion()
    {
        $this->assertNull($this->element->searchNode('cfdi:Impuestos', 'cfdi:Retenciones', 'cfdi:Retencion'));
        $first = $this->element->addRetencion(['name' => 'first']);
        $this->assertInstanceOf(Retencion::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertSame($first, $this->element->searchNode('cfdi:Impuestos', 'cfdi:Retenciones', 'cfdi:Retencion'));
    }

    public function testMultiRetencion()
    {
        $parent = $this->element->getImpuestos()->getRetenciones();
        $this->assertCount(0, $parent);
        $this->element->multiRetencion(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $parent);
        $this->assertSame('first', $parent->searchAttribute('cfdi:Retencion', 'id'));
    }

    public function testAddInformacionAduanera()
    {
        $parent = $this->element;
        $this->assertCount(0, $parent);
        $this->element->addInformacionAduanera(['id' => 'first']);
        $this->assertCount(1, $parent);
        $this->assertSame('first', $parent->searchAttribute('cfdi:InformacionAduanera', 'id'));
    }

    public function testMultiInformacionAduanera()
    {
        $parent = $this->element;
        $this->assertCount(0, $parent);
        $this->element->multiInformacionAduanera(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $parent);
        $this->assertSame('first', $parent->searchAttribute('cfdi:InformacionAduanera', 'id'));
    }

    public function testGetCuentaPredial()
    {
        $this->assertNull($this->element->searchNode('cfdi:CuentaPredial'));
        $child = $this->element->getCuentaPredial();
        $this->assertInstanceOf(CuentaPredial::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:CuentaPredial'));
    }

    public function testAddCuentaPredial()
    {
        $parent = $this->element;
        $this->assertCount(0, $parent);
        $this->element->addCuentaPredial(['id' => 'first']);
        $this->assertCount(1, $parent);
        $this->assertSame('first', $parent->searchAttribute('cfdi:CuentaPredial', 'id'));
    }
}
