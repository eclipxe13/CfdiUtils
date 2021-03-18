<?php

namespace CfdiUtilsTests\Elements\ImpLocal10;

use CfdiUtils\Elements\ImpLocal10\ImpuestosLocales;
use CfdiUtils\Elements\ImpLocal10\RetencionesLocales;
use CfdiUtils\Elements\ImpLocal10\TrasladosLocales;
use PHPUnit\Framework\TestCase;

final class ImpuestosLocalesTest extends TestCase
{
    /** @var ImpuestosLocales */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new ImpuestosLocales();
    }

    public function testConstructedObject()
    {
        $this->assertSame('implocal:ImpuestosLocales', $this->element->getElementName());
    }

    public function testRetencion()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // add insert first element
        $first = $this->element->addRetencionLocal(['id' => 'first']);
        $this->assertInstanceOf(RetencionesLocales::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // add insert second element and is not the same
        $second = $this->element->addRetencionLocal(['id' => 'second']);
        $this->assertSame('second', $second['id']);
        $this->assertCount(2, $this->element);
        $this->assertNotSame($first, $second);
    }

    public function testTraslado()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // add insert first element
        $first = $this->element->addTrasladoLocal(['id' => 'first']);
        $this->assertInstanceOf(TrasladosLocales::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // add insert second element and is not the same
        $second = $this->element->addTrasladoLocal(['id' => 'second']);
        $this->assertSame('second', $second['id']);
        $this->assertCount(2, $this->element);
        $this->assertNotSame($first, $second);
    }

    public function testChildrenOrder()
    {
        // add in inverse order
        $this->element->addTrasladoLocal();
        $this->element->addRetencionLocal();

        // retrieve in correct order
        $this->assertInstanceOf(RetencionesLocales::class, $this->element->children()->get(0));
        $this->assertInstanceOf(TrasladosLocales::class, $this->element->children()->get(1));
    }
}
