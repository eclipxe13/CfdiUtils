<?php
namespace CfdiUtilsTests\Elements\Pagos10;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Elements\Pagos10\Pagos;
use PHPUnit\Framework\TestCase;

class PagosTest extends TestCase
{
    /** @var Pagos */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Pagos();
    }

    public function testConstructedObject()
    {
        $this->assertSame('pago10:Pagos', $this->element->getElementName());
    }

    public function testPagos()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // add insert first element
        $first = $this->element->addPago(['id' => 'first']);
        $this->assertInstanceOf(Pago::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // add insert second element and is not the same
        $second = $this->element->addPago(['id' => 'second']);
        $this->assertSame('second', $second['id']);
        $this->assertCount(2, $this->element);
        $this->assertNotSame($first, $second);
    }
}
