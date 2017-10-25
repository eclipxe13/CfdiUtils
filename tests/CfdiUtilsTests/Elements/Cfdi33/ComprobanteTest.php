<?php
namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\CfdiRelacionado;
use CfdiUtils\Elements\Cfdi33\CfdiRelacionados;
use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Elements\Cfdi33\Concepto;
use CfdiUtils\Elements\Cfdi33\Conceptos;
use CfdiUtils\Elements\Cfdi33\Emisor;
use CfdiUtils\Elements\Cfdi33\Receptor;
use PHPUnit\Framework\TestCase;

class ComprobanteTest extends TestCase
{
    /**@var Comprobante */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Comprobante();
    }

    public function testGetElementName()
    {
        $this->assertSame('cfdi:Comprobante', $this->element->getElementName());
    }

    public function testGetCfdiRelacionados()
    {
        $this->assertNull($this->element->searchNode('cfdi:CfdiRelacionados'));
        $child = $this->element->getCfdiRelacionados();
        $this->assertInstanceOf(CfdiRelacionados::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:CfdiRelacionados'));
    }

    public function testAddRelacionado()
    {
        $first = $this->element->addCfdiRelacionado(['UUID' => 'FOO']);
        $this->assertInstanceOf(CfdiRelacionado::class, $first);
        $this->assertSame('FOO', $first['UUID']);
        $this->assertCount(1, $this->element->getCfdiRelacionados());
    }

    public function testGetEmisor()
    {
        $this->assertNull($this->element->searchNode('cfdi:Emisor'));
        $child = $this->element->getEmisor();
        $this->assertInstanceOf(Emisor::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:Emisor'));
    }

    public function testAddEmisor()
    {
        $first = $this->element->addEmisor(['Rfc' => 'FOO']);
        $this->assertInstanceOf(Emisor::class, $first);
        $this->assertSame('FOO', $first['Rfc']);

        $second = $this->element->addEmisor(['Rfc' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['Rfc']);
    }

    public function testGetReceptor()
    {
        $this->assertNull($this->element->searchNode('cfdi:Receptor'));
        $child = $this->element->getReceptor();
        $this->assertInstanceOf(Receptor::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:Receptor'));
    }

    public function testAddReceptor()
    {
        $first = $this->element->addReceptor(['Rfc' => 'BAZ']);
        $this->assertInstanceOf(Receptor::class, $first);
        $this->assertSame('BAZ', $first['Rfc']);

        $second = $this->element->addReceptor(['Rfc' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['Rfc']);
    }

    public function testGetConceptos()
    {
        $this->assertNull($this->element->searchNode('cfdi:Conceptos'));
        $child = $this->element->getConceptos();
        $this->assertInstanceOf(Conceptos::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:Conceptos'));
    }

    public function testAddConcepto()
    {
        $this->assertCount(0, $this->element);

        $first = $this->element->addConcepto(['name' => 'FOO']);
        $this->assertInstanceOf(Concepto::class, $first);
        $this->assertSame('FOO', $first['name']);
        $this->assertCount(1, $this->element);
    }

    public function testHasFixedAttributes()
    {
        $this->assertSame('3.3', $this->element['Version']);
        $this->assertSame('http://www.sat.gob.mx/cfd/3', $this->element['xmlns:cfdi']);
        $this->assertNotEmpty($this->element['xmlns:xsi']);
        $this->assertNotEmpty($this->element['xsi:schemaLocation']);
    }
}
