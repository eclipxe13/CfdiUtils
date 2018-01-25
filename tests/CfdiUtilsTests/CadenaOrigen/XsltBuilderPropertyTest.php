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

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('current xsltBuilder');
        $implementation->getXsltBuilder();
    }

    public function testXsltBuilderProperty()
    {
        $implementation = $this->createImplementation();

        $builder = new DOMBuilder();
        $implementation->setXsltBuilder($builder);
        $this->assertSame($builder, $implementation->getXsltBuilder());
    }

    protected function createImplementation(): XsltBuilderPropertyInterface
    {
        return new class() implements XsltBuilderPropertyInterface {
            use XsltBuilderPropertyTrait;
        };
    }
}
