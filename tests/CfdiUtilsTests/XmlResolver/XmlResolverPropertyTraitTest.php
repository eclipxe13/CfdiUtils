<?php

namespace CfdiUtilsTests\XmlResolver;

use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;
use CfdiUtilsTests\TestCase;

final class XmlResolverPropertyTraitTest extends TestCase
{
    /** @var XmlResolverPropertyInterface */
    private XmlResolverPropertyInterface $specimen;

    protected function setUp(): void
    {
        parent::setUp();
        $this->specimen = new class () implements XmlResolverPropertyInterface {
            use XmlResolverPropertyTrait;
        };
    }

    public function testInitialState(): void
    {
        $this->assertFalse($this->specimen->hasXmlResolver());
    }

    public function testGetterFailsOnInitialState(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('There is no current xmlResolver');
        $this->specimen->getXmlResolver();
    }

    public function testSetterToValueAndToNull(): void
    {
        $xmlResolver = new XmlResolver();
        $this->specimen->setXmlResolver($xmlResolver);
        $this->assertTrue($this->specimen->hasXmlResolver());

        $this->specimen->setXmlResolver(null);
        $this->assertFalse($this->specimen->hasXmlResolver());
    }

    public function testGetterFailsAfterSettingResolverToNull(): void
    {
        $xmlResolver = new XmlResolver();
        $this->specimen->setXmlResolver($xmlResolver);
        $this->assertTrue($this->specimen->hasXmlResolver());

        $this->specimen->setXmlResolver(null);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('There is no current xmlResolver');
        $this->specimen->getXmlResolver();
    }
}
