<?php

namespace CfdiUtilsTests\Cleaner\BeforeLoad;

use CfdiUtils\Cleaner\BeforeLoad\BeforeLoadCleanerInterface;
use CfdiUtils\Cleaner\BeforeLoad\RemoveDuplicatedCfdi3Namespace;
use CfdiUtilsTests\TestCase;

final class RemoveDuplicatedCfdi3NamespaceTest extends TestCase
{
    public function testImplementsBeforeLoadCleanerInterface()
    {
        $this->assertInstanceOf(BeforeLoadCleanerInterface::class, new RemoveDuplicatedCfdi3Namespace());
    }

    public function testCleanWithValue()
    {
        $sample = '<cfdi:Comprobante xmlns="http://www.sat.gob.mx/cfd/3"'
            . ' xmlns:cfdi="http://www.sat.gob.mx/cfd/3"/>';
        $expected = '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3"/>';
        $cleaner = new RemoveDuplicatedCfdi3Namespace();
        $this->assertSame($expected, $cleaner->clean($sample));
    }
}
