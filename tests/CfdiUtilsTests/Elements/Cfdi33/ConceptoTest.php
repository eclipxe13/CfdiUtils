<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\ComplementoConcepto;
use CfdiUtils\Elements\Cfdi33\Concepto;
use CfdiUtils\Elements\Cfdi33\ConceptoImpuestos;
use CfdiUtils\Elements\Cfdi33\CuentaPredial;
use CfdiUtils\Elements\Cfdi33\Impuestos;
use CfdiUtils\Elements\Cfdi33\InformacionAduanera;
use CfdiUtils\Elements\Cfdi33\Parte;
use PHPUnit\Framework\TestCase;

final class ConceptoTest extends TestCase
{
    public Concepto $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new Concepto();
    }

    public function testGetImpuestos(): void
    {
        $this->assertNull($this->element->searchNode('cfdi:Impuestos'));
        $child = $this->element->getImpuestos();
        $this->assertInstanceOf(ConceptoImpuestos::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:Impuestos'));
    }

    public function testGetCuentaPredial(): void
    {
        $this->assertNull($this->element->searchNode('cfdi:CuentaPredial'));
        $child = $this->element->getCuentaPredial();
        $this->assertInstanceOf(CuentaPredial::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:CuentaPredial'));
    }

    public function testAddCuentaPredial(): void
    {
        $parent = $this->element;
        $this->assertCount(0, $parent);

        $first = $parent->addCuentaPredial(['id' => 'first']);
        $this->assertCount(1, $parent);
        $this->assertInstanceOf(CuentaPredial::class, $first);
        $this->assertSame('first', $parent->searchAttribute('cfdi:CuentaPredial', 'id'));

        $second = $parent->addCuentaPredial(['ID' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['ID']);
    }

    public function testGetComplementoConcepto(): void
    {
        $this->assertNull($this->element->searchNode('cfdi:ComplementoConcepto'));
        $child = $this->element->getComplementoConcepto();
        $this->assertInstanceOf(ComplementoConcepto::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:ComplementoConcepto'));
    }

    public function testAddComplementoConcepto(): void
    {
        $parent = $this->element;
        $this->assertCount(0, $parent);

        $first = $parent->addComplementoConcepto(['ID' => '123AD']);
        $this->assertCount(1, $parent);
        $this->assertInstanceOf(ComplementoConcepto::class, $first);
        $this->assertSame('123AD', $first['ID']);

        $second = $parent->addComplementoConcepto(['ID' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['ID']);
    }

    public function testAddParte(): void
    {
        // no childs
        $parent = $this->element;
        $this->assertCount(0, $parent);

        // add first child
        $first = $parent->addParte(['name' => 'first']);
        $this->assertInstanceOf(Parte::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertCount(1, $parent);

        // add second child
        $parent->addParte();
        $this->assertCount(2, $parent);
    }

    public function testMultiParte(): void
    {
        $node = $this->element;
        $this->assertCount(0, $node);
        $multiReturn = $node->multiParte(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertSame($multiReturn, $node);
        $this->assertCount(2, $node);
        $this->assertSame('first', $node->searchAttribute('cfdi:Parte', 'id'));
    }

    public function testChildrenOrder(): void
    {
        // add in inverse order
        $this->element->addParte();
        $this->element->getComplementoConcepto();
        $this->element->getCuentaPredial();
        $this->element->addInformacionAduanera();
        $this->element->getImpuestos();

        // retrieve in correct order
        $this->assertInstanceOf(Impuestos::class, $this->element->children()->get(0));
        $this->assertInstanceOf(InformacionAduanera::class, $this->element->children()->get(1));
        $this->assertInstanceOf(CuentaPredial::class, $this->element->children()->get(2));
        $this->assertInstanceOf(ComplementoConcepto::class, $this->element->children()->get(3));
        $this->assertInstanceOf(Parte::class, $this->element->children()->get(4));
    }
}
