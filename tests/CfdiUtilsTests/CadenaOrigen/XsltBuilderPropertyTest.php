<?php
namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtilsTests\TestCase;

class XsltBuilderPropertyTest extends TestCase
{
    public function testXsltBuilderPropertyWithoutSet()
    {
        $implementation = $this->createImplementation();
        $this->assertFalse($implementation->hasXsltBuilder());

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('current xsltBuilder');
        $implementation->getXsltBuilder();
    }

    public function testXsltBuilderProperty()
    {
        $builder = new DOMBuilder();
        $implementation = $this->createImplementation();

        $implementation->setXsltBuilder($builder);
        $this->assertTrue($implementation->hasXsltBuilder());
        $this->assertSame($builder, $implementation->getXsltBuilder());

        $implementation->setXsltBuilder(null);
        $this->assertFalse($implementation->hasXsltBuilder());
    }

    protected function createImplementation(): XsltBuilderPropertyInterface
    {
        return new class() implements XsltBuilderPropertyInterface {
            use XsltBuilderPropertyTrait;
        };
    }
}
