<?php

namespace CfdiUtilsTests\Elements\Cce11\Traits;

use CfdiUtils\Elements\Cce11\Domicilio;
use PHPUnit\Framework\TestCase;

final class DomicilioTraitTest extends TestCase
{
    /** @var UseDomicilio */
    public $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new UseDomicilio();
    }

    public function testDomicilio()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // get retrieve and insert the element
        $first = $this->element->getDomicilio();
        $this->assertInstanceOf(Domicilio::class, $first);
        $this->assertCount(1, $this->element);

        // get (again) retrieve the same element
        $this->assertSame($first, $this->element->getDomicilio());
        $this->assertCount(1, $this->element);

        // add works with the same element
        $second = $this->element->addDomicilio(['foo' => 'bar']);
        $this->assertInstanceOf(Domicilio::class, $second);
        $this->assertCount(1, $this->element);
        $this->assertSame($second, $first);
        $this->assertSame('bar', $first['foo']);
    }
}
