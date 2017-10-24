<?php
namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\DefaultLocations;
use PHPUnit\Framework\TestCase;

class DefaultLocationsTest extends TestCase
{
    public function testLocations()
    {
        $this->assertSame(DefaultLocations::XSLT_32, DefaultLocations::location('3.2'));
        $this->assertSame(DefaultLocations::XSLT_33, DefaultLocations::location('3.3'));
    }

    public function testLocationsThrowExceptionWhenVersionIsNotFound()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('1.2');
        DefaultLocations::location('1.2');
    }
}
