<?php

namespace CfdiUtilsTests\Elements\Cce11;

use CfdiUtils\Elements\Cce11\Destinatario;
use CfdiUtils\Elements\Cce11\Domicilio;
use PHPUnit\Framework\TestCase;

class DestinatarioTest extends TestCase
{
    /** @var Destinatario */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Destinatario();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cce11:Destinatario', $this->element->getElementName());
    }

    public function testDomicilio()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // add insert first element
        $first = $this->element->addDomicilio(['id' => 'first']);
        $this->assertInstanceOf(Domicilio::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // add insert second element and is not the same
        $second = $this->element->addDomicilio(['id' => 'second']);
        $this->assertSame('second', $second['id']);
        $this->assertCount(2, $this->element);
        $this->assertNotSame($first, $second);
    }
}
