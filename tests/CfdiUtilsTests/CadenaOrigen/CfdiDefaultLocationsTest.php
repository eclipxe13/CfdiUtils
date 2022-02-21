<?php

namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\CfdiDefaultLocations;
use CfdiUtilsTests\TestCase;

final class CfdiDefaultLocationsTest extends TestCase
{
    public function providerLocationByVersion(): array
    {
        return [
            '3.2' => ['3.2', CfdiDefaultLocations::XSLT_32],
            '3.3' => ['3.3', CfdiDefaultLocations::XSLT_33],
            '4.0' => ['4.0', CfdiDefaultLocations::XSLT_40],
        ];
    }

    /** @dataProvider providerLocationByVersion */
    public function testLocationByVersion(string $version, string $location): void
    {
        $this->assertSame($location, CfdiDefaultLocations::location($version));
    }
}
