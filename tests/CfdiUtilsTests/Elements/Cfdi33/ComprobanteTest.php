<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Addenda;
use CfdiUtils\Elements\Cfdi33\CfdiRelacionado;
use CfdiUtils\Elements\Cfdi33\CfdiRelacionados;
use CfdiUtils\Elements\Cfdi33\Complemento;
use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Elements\Cfdi33\Concepto;
use CfdiUtils\Elements\Cfdi33\Conceptos;
use CfdiUtils\Elements\Cfdi33\Emisor;
use CfdiUtils\Elements\Cfdi33\Impuestos;
use CfdiUtils\Elements\Cfdi33\Receptor;
use CfdiUtils\Nodes\Node;
use PHPUnit\Framework\TestCase;

final class ComprobanteTest extends TestCase
{
    /**@var Comprobante */
    public $element;

    protected function setUp(): void
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

    public function testMultiRelacionado()
    {
        $self = $this->element->multiCfdiRelacionado(
            ['UUID' => 'FOO'],
            ['UUID' => 'BAR']
        );
        $this->assertSame($this->element, $self);
        $this->assertCount(2, $this->element->getCfdiRelacionados());
        $this->assertSame('FOO', $this->element->getCfdiRelacionados()->children()->get(0)['UUID']);
        $this->assertSame('BAR', $this->element->getCfdiRelacionados()->children()->get(1)['UUID']);
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

    public function testGetImpuestos()
    {
        $this->assertNull($this->element->searchNode('cfdi:Impuestos'));
        $child = $this->element->getImpuestos();
        $this->assertInstanceOf(Impuestos::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:Impuestos'));
    }

    public function testAddImpuestos()
    {
        $first = $this->element->addImpuestos(['Foo' => 'Bar']);
        $this->assertInstanceOf(Impuestos::class, $first);
        $this->assertSame('Bar', $first['Foo']);

        $second = $this->element->addImpuestos(['Foo' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['Foo']);
    }

    public function testAddConcepto()
    {
        $this->assertCount(0, $this->element);

        $first = $this->element->addConcepto(['name' => 'FOO']);
        $this->assertInstanceOf(Concepto::class, $first);
        $this->assertSame('FOO', $first['name']);
        $this->assertCount(1, $this->element);
    }

    public function testGetComplemento()
    {
        $this->assertNull($this->element->searchNode('cfdi:Complemento'));
        $child = $this->element->getComplemento();
        $this->assertInstanceOf(Complemento::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:Complemento'));
    }

    public function testAddComplemento()
    {
        $this->assertCount(0, $this->element);

        $child = new Node('first');
        $addReturn = $this->element->addComplemento($child);
        $this->assertCount(1, $this->element);
        $this->assertSame($child, $this->element->searchNode('cfdi:Complemento', 'first'));
        $this->assertSame($addReturn, $this->element);
    }

    public function testGetAddenda()
    {
        $this->assertNull($this->element->searchNode('cfdi:Addenda'));
        $child = $this->element->getAddenda();
        $this->assertInstanceOf(Addenda::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:Addenda'));
    }

    public function testAddAddenda()
    {
        $this->assertCount(0, $this->element);

        $child = new Node('first');
        $addReturn = $this->element->addAddenda($child);
        $this->assertCount(1, $this->element);
        $this->assertSame($child, $this->element->searchNode('cfdi:Addenda', 'first'));
        $this->assertSame($addReturn, $this->element);
    }

    public function testHasFixedAttributes()
    {
        $namespace = 'http://www.sat.gob.mx/cfd/3';
        $this->assertSame('3.3', $this->element['Version']);
        $this->assertSame($namespace, $this->element['xmlns:cfdi']);
        $this->assertStringStartsWith($namespace . ' http://', $this->element['xsi:schemaLocation']);
        $this->assertNotEmpty($this->element['xmlns:xsi']);
    }

    public function testChildrenOrder()
    {
        // add in inverse order
        $this->element->getAddenda();
        $this->element->getComplemento();
        $this->element->getImpuestos();
        $this->element->getConceptos();
        $this->element->getReceptor();
        $this->element->getEmisor();
        $this->element->getCfdiRelacionados();

        // retrieve in correct order
        $this->assertInstanceOf(CfdiRelacionados::class, $this->element->children()->get(0));
        $this->assertInstanceOf(Emisor::class, $this->element->children()->get(1));
        $this->assertInstanceOf(Receptor::class, $this->element->children()->get(2));
        $this->assertInstanceOf(Conceptos::class, $this->element->children()->get(3));
        $this->assertInstanceOf(Impuestos::class, $this->element->children()->get(4));
        $this->assertInstanceOf(Complemento::class, $this->element->children()->get(5));
        $this->assertInstanceOf(Addenda::class, $this->element->children()->get(6));
    }
}
