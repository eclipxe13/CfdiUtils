<?php

namespace CfdiUtilsTests;

use CfdiUtils\CfdiCreator33;
use PHPUnit\Framework\MockObject\MockObject;

final class CfdiCreatorToStringTest extends TestCase
{
    public function testWhenCastingToStringWithExceptionOnlyReturnsAnEmptyString(): void
    {
        /** @var CfdiCreator33&MockObject $cfdiCreator */
        $cfdiCreator = $this->getMockBuilder(CfdiCreator33::class)
            ->setMethods(['asXml'])
            ->getMock();

        $cfdiCreator->method('asXml')->willThrowException(new \RuntimeException('exception'));

        $this->assertSame('', (string)$cfdiCreator);
    }

    public function testCastToStringReturnAValidXml(): void
    {
        $cfdiCreator = new CfdiCreator33();
        $xml = $cfdiCreator->asXml();
        $this->assertNotEmpty($xml);
        $this->assertSame($xml, (string)$cfdiCreator);
    }
}
