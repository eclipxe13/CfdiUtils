<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Retencion;
use CfdiUtils\Elements\Cfdi33\Retenciones;
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

    public function testGetElementName(): void
    {
        $this->assertSame('cfdi:Retenciones', $this->element->getElementName());
    }

    public function testAddRetencion(): void
    {
        $parent = $this->element;

        // no childs
        $this->assertCount(0, $parent);

        // add first child
        $first = $this->element->addRetencion(['name' => 'first']);
        $this->assertInstanceOf(Retencion::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertCount(1, $this->element);

        // add second child
        $second = $this->element->addRetencion();
        $this->assertCount(2, $this->element);

        // test that first and second are not the same
        $this->assertNotSame($first, $second);
    }

    public function testMultiRetencion(): void
    {
        $node = $this->element;
        $this->assertCount(0, $node);
        $multiReturn = $node->multiRetencion(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertSame($multiReturn, $node);
        $this->assertCount(2, $node);
        $this->assertSame('first', $node->searchAttribute('cfdi:Retencion', 'id'));
    }
}
