<?php
namespace CfdiUtilsTests\Validate;

use CfdiUtils\Validate\Hydrater;
use CfdiUtilsTests\TestCase;
use CfdiUtilsTests\Validate\FakeObjects\ImplementationRequireXmlResolverInterface;
use CfdiUtilsTests\Validate\FakeObjects\ImplementationRequireXmlStringInterface;

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
        $xmlResolver = $this->newResolver();

        $hydrater->setXmlResolver($xmlResolver);
        $this->assertSame($xmlResolver, $hydrater->getXmlResolver());

        $container = new ImplementationRequireXmlResolverInterface();
        $hydrater->hydrate($container);
        $this->assertSame($hydrater->getXmlResolver(), $container->getXmlResolver());
    }
}
