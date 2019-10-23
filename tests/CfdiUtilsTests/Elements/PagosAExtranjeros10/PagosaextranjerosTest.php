<?php

namespace CfdiUtilsTests\Elements\PagosAExtranjeros10;

use CfdiUtils\Elements\PagosAExtranjeros10\Beneficiario;
use CfdiUtils\Elements\PagosAExtranjeros10\NoBeneficiario;
use CfdiUtils\Elements\PagosAExtranjeros10\Pagosaextranjeros;
use PHPUnit\Framework\TestCase;

class PagosaextranjerosTest extends TestCase
{
    /** @var Pagosaextranjeros */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Pagosaextranjeros();
    }

    public function testGetElementName()
    {
        $this->assertSame('pagosaextranjeros:Pagosaextranjeros', $this->element->getElementName());
    }

    public function testGetNoBeneficiario()
    {
        $this->assertNull($this->element->searchNode('pagosaextranjeros:NoBeneficiario'));
        $child = $this->element->getNoBeneficiario();
        $this->assertInstanceOf(NoBeneficiario::class, $child);
        $this->assertSame($child, $this->element->searchNode('pagosaextranjeros:NoBeneficiario'));
    }

    public function testAddNoBeneficiario()
    {
        $first = $this->element->addNoBeneficiario(['Rfc' => 'FOO']);
        $this->assertInstanceOf(NoBeneficiario::class, $first);
        $this->assertSame('FOO', $first['Rfc']);

        $second = $this->element->addNoBeneficiario(['Rfc' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['Rfc']);
    }

    public function testGetBeneficiario()
    {
        $this->assertNull($this->element->searchNode('pagosaextranjeros:Beneficiario'));
        $child = $this->element->getBeneficiario();
        $this->assertInstanceOf(Beneficiario::class, $child);
        $this->assertSame($child, $this->element->searchNode('pagosaextranjeros:Beneficiario'));
    }

    public function testAddBeneficiario()
    {
        $first = $this->element->addBeneficiario(['Rfc' => 'BAZ']);
        $this->assertInstanceOf(Beneficiario::class, $first);
        $this->assertSame('BAZ', $first['Rfc']);

        $second = $this->element->addBeneficiario(['Rfc' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['Rfc']);
    }

    public function testHasFixedAttributes()
    {
        $namespace = 'http://www.sat.gob.mx/esquemas/retencionpago/1/pagosaextranjeros';
        $this->assertSame('1.0', $this->element['Version']);
        $this->assertSame($namespace, $this->element['xmlns:pagosaextranjeros']);
        $this->assertStringStartsWith($namespace . ' http://', $this->element['xsi:schemaLocation']);
    }

    public function testChildrenOrder()
    {
        // add in inverse order
        $this->element->getBeneficiario();
        $this->element->getNoBeneficiario();

        // retrieve in correct order
        $this->assertInstanceOf(NoBeneficiario::class, $this->element->children()->get(0));
        $this->assertInstanceOf(Beneficiario::class, $this->element->children()->get(1));
    }
}
