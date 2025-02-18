<?php

namespace CfdiUtilsTests\Cleaner\BeforeLoad;

use CfdiUtils\Cleaner\BeforeLoad\BeforeLoadCleanerInterface;
use CfdiUtils\Cleaner\BeforeLoad\ChangeXmlnsSchemaLocation;
use CfdiUtilsTests\TestCase;

final class ChangeXmlnsSchemaLocationTest extends TestCase
{
    public function testImplementsBeforeLoadCleanerInterface(): void
    {
        $this->assertInstanceOf(BeforeLoadCleanerInterface::class, new ChangeXmlnsSchemaLocation());
    }

    public function testCleanWithValue(): void
    {
        $sample = '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3"'
            . ' xmlns:schemaLocation="http://www.sat.gob.mx/cfd/3 location"/>';
        $expected = '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3"'
            . ' xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 location"/>';
        $cleaner = new ChangeXmlnsSchemaLocation();
        $this->assertSame($expected, $cleaner->clean($sample));
    }
}
