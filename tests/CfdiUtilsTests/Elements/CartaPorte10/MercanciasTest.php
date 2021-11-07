<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Mercancia;
use CfdiUtils\Elements\CartaPorte10\Mercancias;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Mercancias
 */
final class MercanciasTest extends TestCase
{
    /** @var Mercancias */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Mercancias();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:Mercancias', $this->element->getElementName());
    }

    public function testAddMercancia()
    {
        // insert first element
        $first = $this->element->addMercancia(['id' => 'first']);
        $this->assertInstanceOf(Mercancia::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addMercancia(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiMercancia()
    {
        // insert first element
        $ubicaciones = $this->element->multiMercancia(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $ubicaciones);
        $this->assertSame($this->element, $ubicaciones);
    }

}
