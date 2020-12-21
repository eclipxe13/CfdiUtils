<?php

namespace CfdiUtilsTests\Elements\Dividendos10;

use CfdiUtils\Elements\Dividendos10\Dividendos;
use CfdiUtils\Elements\Dividendos10\DividOUtil;
use CfdiUtils\Elements\Dividendos10\Remanente;
use PHPUnit\Framework\TestCase;

class DividendosTest extends TestCase
{
    /** @var Dividendos */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Dividendos();
    }

    public function testGetElementName()
    {
        $this->assertSame('dividendos:Dividendos', $this->element->getElementName());
    }

    public function testGetDividOUtil()
    {
        $this->assertNull($this->element->searchNode('dividendos:DividOUtil'));
        $child = $this->element->getDividOUtil();
        $this->assertInstanceOf(DividOUtil::class, $child);
        $this->assertSame($child, $this->element->searchNode('dividendos:DividOUtil'));
    }

    public function testAddDividOUtil()
    {
        $first = $this->element->addDividOUtil(['Rfc' => 'FOO']);
        $this->assertInstanceOf(DividOUtil::class, $first);
        $this->assertSame('FOO', $first['Rfc']);

        $second = $this->element->addDividOUtil(['Rfc' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['Rfc']);
    }

    public function testGetRemanente()
    {
        $this->assertNull($this->element->searchNode('dividendos:Remanente'));
        $child = $this->element->getRemanente();
        $this->assertInstanceOf(Remanente::class, $child);
        $this->assertSame($child, $this->element->searchNode('dividendos:Remanente'));
    }

    public function testAddRemanente()
    {
        $first = $this->element->addRemanente(['Rfc' => 'BAZ']);
        $this->assertInstanceOf(Remanente::class, $first);
        $this->assertSame('BAZ', $first['Rfc']);

        $second = $this->element->addRemanente(['Rfc' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['Rfc']);
    }

    public function testHasFixedAttributes()
    {
        $namespace = 'http://www.sat.gob.mx/esquemas/retencionpago/1/dividendos';
        $this->assertSame('1.0', $this->element['Version']);
        $this->assertSame($namespace, $this->element['xmlns:dividendos']);
        $this->assertStringStartsWith($namespace . ' http://', $this->element['xsi:schemaLocation']);
    }

    public function testChildrenOrder()
    {
        // add in inverse order
        $this->element->getRemanente();
        $this->element->getDividOUtil();

        // retrieve in correct order
        $this->assertInstanceOf(DividOUtil::class, $this->element->children()->get(0));
        $this->assertInstanceOf(Remanente::class, $this->element->children()->get(1));
    }
}
