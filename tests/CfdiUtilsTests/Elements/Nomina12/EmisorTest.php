<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\Emisor;
use CfdiUtils\Elements\Nomina12\EntidadSNCF;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\Emisor
 */
final class EmisorTest extends TestCase
{
    /** @var Emisor */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Emisor();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('nomina12:Emisor', $this->element->getElementName());
    }

    public function testGetEntidadSNCF(): void
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:EntidadSNCF'));

        $first = $this->element->getEntidadSNCF();
        $this->assertCount(1, $this->element->searchNodes('nomina12:EntidadSNCF'));

        $second = $this->element->getEntidadSNCF();
        $this->assertCount(1, $this->element->searchNodes('nomina12:EntidadSNCF'));

        $this->assertSame($first, $second);
    }

    public function testAddEntidadSNCF(): void
    {
        // insert first element
        $first = $this->element->addEntidadSNCF(['id' => 'first']);
        $this->assertInstanceOf(EntidadSNCF::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addEntidadSNCF(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }
}
