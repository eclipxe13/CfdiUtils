<?php

namespace CfdiUtilsTests\Validate;

use CfdiUtils\Validate\Discoverer;
use CfdiUtils\Validate\MultiValidatorFactory;
use CfdiUtils\Validate\Xml\XmlFollowSchema;
use PHPUnit\Framework\TestCase;

final class MultiValidatorFactoryTest extends TestCase
{
    public function testConstructWithoutArguments(): void
    {
        $factory = new MultiValidatorFactory();
        $this->assertInstanceOf(Discoverer::class, $factory->getDiscoverer());
    }

    public function testConstructWithDiscoverer(): void
    {
        $discoverer = new Discoverer();
        $factory = new MultiValidatorFactory($discoverer);
        $this->assertSame($discoverer, $factory->getDiscoverer());
    }

    public function testCreated33ContainsAtLeastXsdValidator(): void
    {
        $factory = new MultiValidatorFactory();
        $validator = $factory->newCreated33();
        $this->assertFalse($validator->canValidateCfdiVersion('3.2'));
        $this->assertTrue($validator->canValidateCfdiVersion('3.3'));

        $hasXmlFollowSchema = false;
        foreach ($validator as $child) {
            if ($child instanceof XmlFollowSchema) {
                $hasXmlFollowSchema = true;
            }
        }
        $this->assertTrue($hasXmlFollowSchema, 'MultiValidator must implement known XmlFollowSchema');
    }

    public function testReceived33ContainsAtLeastXsdValidator(): void
    {
        $factory = new MultiValidatorFactory();
        $validator = $factory->newReceived33();
        $this->assertFalse($validator->canValidateCfdiVersion('3.2'));
        $this->assertTrue($validator->canValidateCfdiVersion('3.3'));

        $hasXmlFollowSchema = false;
        foreach ($validator as $child) {
            if ($child instanceof XmlFollowSchema) {
                $hasXmlFollowSchema = true;
            }
        }
        $this->assertTrue($hasXmlFollowSchema, 'MultiValidator must implement known XmlFollowSchema');
    }
}
