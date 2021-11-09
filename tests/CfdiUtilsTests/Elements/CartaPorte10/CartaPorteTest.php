<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\CartaPorte;
use CfdiUtils\Elements\CartaPorte10\FiguraTransporte;
use CfdiUtils\Elements\CartaPorte10\Mercancias;
use CfdiUtils\Elements\CartaPorte10\Ubicaciones;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\CartaPorte
 */
final class CartaPorteTest extends TestCase
{
    /** @var CartaPorte */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new CartaPorte();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:CartaPorte', $this->element->getElementName());
    }

    public function testChildrenOrder()
    {
        $expected = [
            'cartaporte:Ubicaciones',
            'cartaporte:Mercancias',
            'cartaporte:FiguraTransporte',
        ];
        $this->assertSame($expected, $this->element->getChildrenOrder());
    }

    public function testFixedVersion()
    {
        $this->assertSame('1.0', $this->element['Version']);
    }

    public function testFixedNamespaceDefinition()
    {
        $namespace = 'http://www.sat.gob.mx/cartaporte';
        $this->assertSame($namespace, $this->element['xmlns:cartaporte']);
        $xsdLocation = 'http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte.xsd';
        $this->assertSame($namespace . ' ' . $xsdLocation, $this->element['xsi:schemaLocation']);
    }

    public function testGetUbicaciones()
    {
        $this->assertCount(0, $this->element->searchNodes('cartaporte:Ubicaciones'));

        $first = $this->element->getUbicaciones();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Ubicaciones'));

        $second = $this->element->getUbicaciones();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Ubicaciones'));

        $this->assertSame($first, $second);
    }

    public function testAddUbicaciones()
    {
        // insert first element
        $first = $this->element->addUbicaciones(['id' => 'first']);
        $this->assertInstanceOf(Ubicaciones::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addUbicaciones(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testGetMercancias()
    {
        $this->assertCount(0, $this->element->searchNodes('cartaporte:Mercancias'));

        $first = $this->element->getMercancias();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Mercancias'));

        $second = $this->element->getMercancias();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Mercancias'));

        $this->assertSame($first, $second);
    }

    public function testAddMercancias()
    {
        // insert first element
        $first = $this->element->addMercancias(['id' => 'first']);
        $this->assertInstanceOf(Mercancias::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addMercancias(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testGetFiguraTransporte()
    {
        $this->assertCount(0, $this->element->searchNodes('cartaporte:FiguraTransporte'));

        $first = $this->element->getFiguraTransporte();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:FiguraTransporte'));

        $second = $this->element->getFiguraTransporte();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:FiguraTransporte'));

        $this->assertSame($first, $second);
    }

    public function testAddFiguraTransporte()
    {
        // insert first element
        $first = $this->element->addFiguraTransporte(['id' => 'first']);
        $this->assertInstanceOf(FiguraTransporte::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addFiguraTransporte(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }
}
