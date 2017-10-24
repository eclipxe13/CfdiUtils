<?php
namespace CfdiUtilsTests\Validate;

use CfdiUtils\Validate\Hydrater;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtilsTests\Validate\FakeObjects\ImplementationRequireXmlResolverInterface;
use CfdiUtilsTests\Validate\FakeObjects\ImplementationRequireXmlStringInterface;
use PHPUnit\Framework\TestCase;

class HydraterTest extends TestCase
{
    public function testHydrateXmlString()
    {
        $hydrater = new Hydrater();

        $hydrater->setXmlString('<root />');
        $this->assertSame('<root />', $hydrater->getXmlString());

        $container = new ImplementationRequireXmlStringInterface();
        $hydrater->hydrate($container);
        $this->assertSame($hydrater->getXmlString(), $container->getXmlString());
    }

    public function testHydrateXmlResolver()
    {
        $hydrater = new Hydrater();
        $xmlResolver = new XmlResolver();

        $hydrater->setXmlResolver($xmlResolver);
        $this->assertSame($xmlResolver, $hydrater->getXmlResolver());

        $container = new ImplementationRequireXmlResolverInterface();
        $hydrater->hydrate($container);
        $this->assertSame($hydrater->getXmlResolver(), $container->getXmlResolver());
    }
}
