<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\AccionesOTitulos;
use CfdiUtils\Elements\Nomina12\HorasExtra;
use CfdiUtils\Elements\Nomina12\Percepcion;
use CfdiUtils\Nodes\Node;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\Percepcion
 */
final class PercepcionTest extends TestCase
{
    /** @var Percepcion */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Percepcion();
    }

    public function testConstructedObject()
    {
        $this->assertSame('nomina12:Percepcion', $this->element->getElementName());
    }

    public function testChildrenOrder()
    {
        $expected = ['nomina12:AccionesOTitulos', 'nomina12:HorasExtra'];
        $this->assertSame($expected, $this->element->getChildrenOrder());
    }

    public function testGetAccionesOTitulos()
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:AccionesOTitulos'));

        $first = $this->element->getAccionesOTitulos();
        $this->assertCount(1, $this->element->searchNodes('nomina12:AccionesOTitulos'));

        $second = $this->element->getAccionesOTitulos();
        $this->assertCount(1, $this->element->searchNodes('nomina12:AccionesOTitulos'));

        $this->assertSame($first, $second);
    }

    public function testAddAccionesOTitulos()
    {
        // insert first element
        $first = $this->element->addAccionesOTitulos(['id' => 'first']);
        $this->assertInstanceOf(AccionesOTitulos::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addAccionesOTitulos(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testAddHorasExtra()
    {
        // insert first element
        $children = [new Node('child-1'), new Node('child-2')];
        $first = $this->element->addHorasExtra(['id' => 'first'], $children);
        $this->assertInstanceOf(HorasExtra::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);
        $this->assertTrue($first->children()->exists($children[0]));
        $this->assertTrue($first->children()->exists($children[1]));

        // insert second element data should return a different element
        $second = $this->element->addHorasExtra(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiHorasExtra()
    {
        $horasExtraes = $this->element->multiHorasExtra(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $horasExtraes);
        $this->assertSame($this->element, $horasExtraes);
    }
}
