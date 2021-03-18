<?php

namespace CfdiUtilsTests\Elements\Retenciones10;

use CfdiUtils\Elements\Retenciones10\Addenda;
use CfdiUtils\Elements\Retenciones10\Complemento;
use CfdiUtils\Elements\Retenciones10\Emisor;
use CfdiUtils\Elements\Retenciones10\ImpRetenidos;
use CfdiUtils\Elements\Retenciones10\Periodo;
use CfdiUtils\Elements\Retenciones10\Receptor;
use CfdiUtils\Elements\Retenciones10\Retenciones;
use CfdiUtils\Elements\Retenciones10\Totales;
use CfdiUtils\Nodes\Node;
use PHPUnit\Framework\TestCase;

final class RetencionesTest extends TestCase
{
    /** @var Retenciones */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Retenciones();
    }

    public function testGetElementName()
    {
        $this->assertSame('retenciones:Retenciones', $this->element->getElementName());
    }

    public function testGetEmisor()
    {
        $this->assertNull($this->element->searchNode('retenciones:Emisor'));
        $child = $this->element->getEmisor();
        $this->assertInstanceOf(Emisor::class, $child);
        $this->assertSame($child, $this->element->searchNode('retenciones:Emisor'));
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
        $this->assertNull($this->element->searchNode('retenciones:Receptor'));
        $child = $this->element->getReceptor();
        $this->assertInstanceOf(Receptor::class, $child);
        $this->assertSame($child, $this->element->searchNode('retenciones:Receptor'));
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

    public function testGetPeriodo()
    {
        $this->assertNull($this->element->searchNode('retenciones:Periodo'));
        $child = $this->element->getPeriodo();
        $this->assertInstanceOf(Periodo::class, $child);
        $this->assertSame($child, $this->element->searchNode('retenciones:Periodo'));
    }

    public function testAddPeriodo()
    {
        $first = $this->element->addPeriodo(['Rfc' => 'BAZ']);
        $this->assertInstanceOf(Periodo::class, $first);
        $this->assertSame('BAZ', $first['Rfc']);

        $second = $this->element->addPeriodo(['Rfc' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['Rfc']);
    }

    public function testGetTotales()
    {
        $this->assertNull($this->element->searchNode('retenciones:Totales'));
        $child = $this->element->getTotales();
        $this->assertInstanceOf(Totales::class, $child);
        $this->assertSame($child, $this->element->searchNode('retenciones:Totales'));
    }

    public function testAddTotales()
    {
        $first = $this->element->addTotales(['Foo' => 'Bar']);
        $this->assertInstanceOf(Totales::class, $first);
        $this->assertSame('Bar', $first['Foo']);

        $second = $this->element->addTotales(['Foo' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['Foo']);
    }

    public function testAddImpRetenidos()
    {
        $first = $this->element->addImpRetenidos(['UUID' => 'FOO']);
        $this->assertInstanceOf(ImpRetenidos::class, $first);
        $this->assertSame('FOO', $first['UUID']);
        $this->assertCount(1, $this->element->getTotales());
    }

    public function testMultiImpRetenidos()
    {
        $self = $this->element->multiImpRetenidos(
            ['UUID' => 'FOO'],
            ['UUID' => 'BAR']
        );
        $this->assertSame($this->element, $self);
        $parent = $this->element->getTotales();
        $this->assertCount(2, $parent);
        $this->assertSame('FOO', $parent->children()->get(0)['UUID']);
        $this->assertSame('BAR', $parent->children()->get(1)['UUID']);
    }

    public function testGetComplemento()
    {
        $this->assertNull($this->element->searchNode('retenciones:Complemento'));
        $child = $this->element->getComplemento();
        $this->assertInstanceOf(Complemento::class, $child);
        $this->assertSame($child, $this->element->searchNode('retenciones:Complemento'));
    }

    public function testAddComplemento()
    {
        $this->assertCount(0, $this->element);

        $child = new Node('first');
        $addReturn = $this->element->addComplemento($child);
        $this->assertCount(1, $this->element);
        $this->assertSame($child, $this->element->searchNode('retenciones:Complemento', 'first'));
        $this->assertSame($addReturn, $this->element);
    }

    public function testGetAddenda()
    {
        $this->assertNull($this->element->searchNode('retenciones:Addenda'));
        $child = $this->element->getAddenda();
        $this->assertInstanceOf(Addenda::class, $child);
        $this->assertSame($child, $this->element->searchNode('retenciones:Addenda'));
    }

    public function testAddAddenda()
    {
        $this->assertCount(0, $this->element);

        $child = new Node('first');
        $addReturn = $this->element->addAddenda($child);
        $this->assertCount(1, $this->element);
        $this->assertSame($child, $this->element->searchNode('retenciones:Addenda', 'first'));
        $this->assertSame($addReturn, $this->element);
    }

    public function testHasFixedAttributes()
    {
        $namespace = 'http://www.sat.gob.mx/esquemas/retencionpago/1';
        $this->assertSame('1.0', $this->element['Version']);
        $this->assertSame($namespace, $this->element['xmlns:retenciones']);
        $this->assertStringStartsWith($namespace . ' http://', $this->element['xsi:schemaLocation']);
        $this->assertNotEmpty($this->element['xmlns:xsi']);
    }

    public function testChildrenOrder()
    {
        // add in inverse order
        $this->element->getAddenda();
        $this->element->getComplemento();
        $this->element->getTotales();
        $this->element->getPeriodo();
        $this->element->getReceptor();
        $this->element->getEmisor();

        // retrieve in correct order
        $this->assertInstanceOf(Emisor::class, $this->element->children()->get(0));
        $this->assertInstanceOf(Receptor::class, $this->element->children()->get(1));
        $this->assertInstanceOf(Periodo::class, $this->element->children()->get(2));
        $this->assertInstanceOf(Totales::class, $this->element->children()->get(3));
        $this->assertInstanceOf(Complemento::class, $this->element->children()->get(4));
        $this->assertInstanceOf(Addenda::class, $this->element->children()->get(5));
    }
}
