<?php
namespace CfdiUtilsTests\Elements\Retenciones10;

use CfdiUtils\Elements\Retenciones10\Extranjero;
use CfdiUtils\Elements\Retenciones10\Nacional;
use CfdiUtils\Elements\Retenciones10\Receptor;
use PHPUnit\Framework\TestCase;

class ReceptorTest extends TestCase
{
    /** @var Receptor */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Receptor();
    }

    public function testGetElementName()
    {
        $this->assertSame('retenciones:Receptor', $this->element->getElementName());
    }

    public function testGetNacionalOverridesGetExtranjeroAndViceversa()
    {
        $this->element->getExtranjero();

        $this->element->getNacional();
        $this->assertCount(1, $this->element);
        $this->assertSame('Nacional', $this->element['Nacionalidad']);

        $this->element->getExtranjero();
        $this->assertCount(1, $this->element);
        $this->assertSame('Extranjero', $this->element['Nacionalidad']);
    }

    public function testAddNacional()
    {
        $first = $this->element->addNacional(['foo' => 'ZOO']);
        $this->assertInstanceOf(Nacional::class, $first);
        $this->assertSame('ZOO', $first['foo']);

        $second = $this->element->addNacional(['foo' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['foo']);

        $this->assertCount(1, $this->element);
        $this->assertSame('Nacional', $this->element['Nacionalidad']);
    }

    public function testAddExtranjero()
    {
        $first = $this->element->addExtranjero(['foo' => 'ZOO']);
        $this->assertInstanceOf(Extranjero::class, $first);
        $this->assertSame('ZOO', $first['foo']);

        $second = $this->element->addExtranjero(['foo' => 'BAR']);
        $this->assertSame($first, $second);
        $this->assertSame('BAR', $first['foo']);

        $this->assertCount(1, $this->element);
        $this->assertSame('Extranjero', $this->element['Nacionalidad']);
    }
}
