<?php
namespace CfdiUtilsTests\Elements\Cce11;

use CfdiUtils\Elements\Cce11\ComercioExterior;
use CfdiUtils\Elements\Cce11\Destinatario;
use CfdiUtils\Elements\Cce11\Emisor;
use CfdiUtils\Elements\Cce11\Mercancia;
use CfdiUtils\Elements\Cce11\Mercancias;
use CfdiUtils\Elements\Cce11\Propietario;
use CfdiUtils\Elements\Cce11\Receptor;
use PHPUnit\Framework\TestCase;

class ComercioExteriorTest extends TestCase
{
    /** @var ComercioExterior */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new ComercioExterior();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cce11:ComercioExterior', $this->element->getElementName());
    }

    public function testEmisor()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // get retrieve and insert the element
        $first = $this->element->getEmisor();
        $this->assertInstanceOf(Emisor::class, $first);
        $this->assertCount(1, $this->element);

        // get (again) retrieve the same element
        $this->assertSame($first, $this->element->getEmisor());
        $this->assertCount(1, $this->element);

        // add works with the same element
        $second = $this->element->addEmisor(['foo' => 'bar']);
        $this->assertInstanceOf(Emisor::class, $second);
        $this->assertCount(1, $this->element);
        $this->assertSame($second, $first);
        $this->assertSame('bar', $first['foo']);
    }

    public function testReceptor()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // get retrieve and insert the element
        $first = $this->element->getReceptor();
        $this->assertInstanceOf(Receptor::class, $first);
        $this->assertCount(1, $this->element);

        // get (again) retrieve the same element
        $this->assertSame($first, $this->element->getReceptor());
        $this->assertCount(1, $this->element);

        // add works with the same element
        $second = $this->element->addReceptor(['foo' => 'bar']);
        $this->assertInstanceOf(Receptor::class, $second);
        $this->assertCount(1, $this->element);
        $this->assertSame($second, $first);
        $this->assertSame('bar', $first['foo']);
    }

    public function testMercancias()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // get retrieve and insert the element
        $first = $this->element->getMercancias();
        $this->assertInstanceOf(Mercancias::class, $first);
        $this->assertCount(1, $this->element);

        // get (again) retrieve the same element
        $this->assertSame($first, $this->element->getMercancias());
        $this->assertCount(1, $this->element);

        // add works with the same element
        $second = $this->element->addMercancias(['foo' => 'bar']);
        $this->assertInstanceOf(Mercancias::class, $second);
        $this->assertCount(1, $this->element);
        $this->assertSame($second, $first);
        $this->assertSame('bar', $first['foo']);
    }

    public function testPropietario()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // add insert first element
        $first = $this->element->addPropietario(['id' => 'first']);
        $this->assertInstanceOf(Propietario::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // add insert second element and is not the same
        $second = $this->element->addPropietario(['id' => 'second']);
        $this->assertSame('second', $second['id']);
        $this->assertCount(2, $this->element);
        $this->assertNotSame($first, $second);
    }

    public function testDestinatario()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // add insert first element
        $first = $this->element->addDestinatario(['id' => 'first']);
        $this->assertInstanceOf(Destinatario::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // add insert second element and is not the same
        $second = $this->element->addDestinatario(['id' => 'second']);
        $this->assertSame('second', $second['id']);
        $this->assertCount(2, $this->element);
        $this->assertNotSame($first, $second);
    }

    public function testAddMercancia()
    {
        $mercancias = $this->element->getMercancias();

        $first = $this->element->addMercancia(['foo' => 'bar']);
        $this->assertInstanceOf(Mercancia::class, $first);
        $this->assertCount(1, $mercancias);
        $this->assertSame('bar', $first['foo']);
        $this->assertSame($first, $mercancias->children()->get(0));

        $second = $this->element->addMercancia();
        $this->assertCount(2, $mercancias);
        $this->assertNotSame($first, $second);
        $this->assertSame($second, $mercancias->children()->get(1));
    }

    public function testChildrenOrder()
    {
        // add in inverse order
        $this->element->getMercancias();
        $this->element->addDestinatario();
        $this->element->getReceptor();
        $this->element->addPropietario();
        $this->element->getEmisor();

        // retrieve in correct order
        $this->assertInstanceOf(Emisor::class, $this->element->children()->get(0));
        $this->assertInstanceOf(Propietario::class, $this->element->children()->get(1));
        $this->assertInstanceOf(Receptor::class, $this->element->children()->get(2));
        $this->assertInstanceOf(Destinatario::class, $this->element->children()->get(3));
        $this->assertInstanceOf(Mercancias::class, $this->element->children()->get(4));
    }
}
