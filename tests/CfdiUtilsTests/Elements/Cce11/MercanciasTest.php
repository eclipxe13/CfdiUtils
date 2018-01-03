<?php
namespace CfdiUtilsTests\Elements\Cce11;

use CfdiUtils\Elements\Cce11\Mercancia;
use CfdiUtils\Elements\Cce11\Mercancias;
use PHPUnit\Framework\TestCase;

class MercanciasTest extends TestCase
{
    /** @var Mercancias */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Mercancias();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cce11:Mercancias', $this->element->getElementName());
    }

    public function testMercancia()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // add insert first element
        $first = $this->element->addMercancia(['id' => 'first']);
        $this->assertInstanceOf(Mercancia::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // add insert second element and is not the same
        $second = $this->element->addMercancia(['id' => 'second']);
        $this->assertSame('second', $second['id']);
        $this->assertCount(2, $this->element);
        $this->assertNotSame($first, $second);
    }
}
