<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\JubilacionPensionRetiro;
use CfdiUtils\Elements\Nomina12\Percepcion;
use CfdiUtils\Elements\Nomina12\Percepciones;
use CfdiUtils\Elements\Nomina12\SeparacionIndemnizacion;
use CfdiUtils\Nodes\Node;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\Percepciones
 */
final class PercepcionesTest extends TestCase
{
    public Percepciones $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Percepciones();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('nomina12:Percepciones', $this->element->getElementName());
    }

    public function testChildrenOrder(): void
    {
        $expected = ['nomina12:Percepcion', 'nomina12:JubilacionPensionRetiro', 'nomina12:SeparacionIndemnizacion'];
        $this->assertSame($expected, $this->element->getChildrenOrder());
    }

    public function testAddPercepcion(): void
    {
        // insert first element
        $children = [new Node('child-1'), new Node('child-2')];
        $first = $this->element->addPercepcion(['id' => 'first'], $children);
        $this->assertInstanceOf(Percepcion::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);
        $this->assertTrue($first->children()->exists($children[0]));
        $this->assertTrue($first->children()->exists($children[1]));

        // insert second element data should return a different element
        $second = $this->element->addPercepcion(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiPercepcion(): void
    {
        $percepciones = $this->element->multiPercepcion(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $percepciones);
        $this->assertSame($this->element, $percepciones);
    }

    public function testGetJubilacionPensionRetiro(): void
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:JubilacionPensionRetiro'));

        $first = $this->element->getJubilacionPensionRetiro();
        $this->assertCount(1, $this->element->searchNodes('nomina12:JubilacionPensionRetiro'));

        $second = $this->element->getJubilacionPensionRetiro();
        $this->assertCount(1, $this->element->searchNodes('nomina12:JubilacionPensionRetiro'));

        $this->assertSame($first, $second);
    }

    public function testAddJubilacionPensionRetiro(): void
    {
        // insert first element
        $first = $this->element->addJubilacionPensionRetiro(['id' => 'first']);
        $this->assertInstanceOf(JubilacionPensionRetiro::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addJubilacionPensionRetiro(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testGetSeparacionIndemnizacion(): void
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:SeparacionIndemnizacion'));

        $first = $this->element->getSeparacionIndemnizacion();
        $this->assertCount(1, $this->element->searchNodes('nomina12:SeparacionIndemnizacion'));

        $second = $this->element->getSeparacionIndemnizacion();
        $this->assertCount(1, $this->element->searchNodes('nomina12:SeparacionIndemnizacion'));

        $this->assertSame($first, $second);
    }

    public function testAddSeparacionIndemnizacion(): void
    {
        // insert first element
        $first = $this->element->addSeparacionIndemnizacion(['id' => 'first']);
        $this->assertInstanceOf(SeparacionIndemnizacion::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addSeparacionIndemnizacion(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }
}
