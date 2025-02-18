<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\AutotransporteFederal;
use CfdiUtils\Elements\CartaPorte10\IdentificacionVehicular;
use CfdiUtils\Elements\CartaPorte10\Remolques;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\AutotransporteFederal
 */
final class AutotransporteFederalTest extends TestCase
{
    /** @var AutotransporteFederal */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new AutotransporteFederal();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:AutotransporteFederal', $this->element->getElementName());
    }

    public function testGetIdentificacionVehicular(): void
    {
        $this->assertCount(0, $this->element->searchNodes('cartaporte:IdentificacionVehicular'));

        $first = $this->element->getIdentificacionVehicular();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:IdentificacionVehicular'));

        $second = $this->element->getIdentificacionVehicular();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:IdentificacionVehicular'));

        $this->assertSame($first, $second);
    }

    public function testAddIdentificacionVehicular(): void
    {
        // insert first element
        $first = $this->element->addIdentificacionVehicular(['id' => 'first']);
        $this->assertInstanceOf(IdentificacionVehicular::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addIdentificacionVehicular(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testGetRemolques(): void
    {
        $this->assertCount(0, $this->element->searchNodes('cartaporte:Remolques'));

        $first = $this->element->getRemolques();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Remolques'));

        $second = $this->element->getRemolques();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Remolques'));

        $this->assertSame($first, $second);
    }

    public function testAddRemolques(): void
    {
        // insert first element
        $first = $this->element->addRemolques(['id' => 'first']);
        $this->assertInstanceOf(Remolques::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addRemolques(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }
}
