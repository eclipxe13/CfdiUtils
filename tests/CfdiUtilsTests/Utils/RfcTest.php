<?php

namespace CfdiUtilsTests\Utils;

use CfdiUtils\Utils\Rfc;
use CfdiUtilsTests\TestCase;

final class RfcTest extends TestCase
{
    public function testCreateRfcPerson(): void
    {
        $input = 'COSC8001137NA';
        $rfc = new Rfc($input);
        $this->assertSame($input, (string) $rfc);
        $this->assertFalse($rfc->isGeneric());
        $this->assertFalse($rfc->isForeign());
        $this->assertFalse($rfc->isMoral());
        $this->assertTrue($rfc->isPerson());
        $this->assertSame('A', $rfc->checkSum());
        $this->assertTrue($rfc->checkSumMatch());
    }

    public function testCreateRfcMoral(): void
    {
        $input = 'DIM8701081LA';
        $rfc = new Rfc($input);
        $this->assertSame($input, (string) $rfc);
        $this->assertFalse($rfc->isGeneric());
        $this->assertFalse($rfc->isForeign());
        $this->assertTrue($rfc->isMoral());
        $this->assertFalse($rfc->isPerson());
    }

    public function testCreateWithForeign(): void
    {
        $rfc = new Rfc(Rfc::RFC_FOREIGN);
        $this->assertFalse($rfc->isGeneric());
        $this->assertTrue($rfc->isForeign());
        $this->assertFalse($rfc->isMoral());
        $this->assertTrue($rfc->isPerson());
    }

    public function testCreateWithGeneric(): void
    {
        $rfc = new Rfc(Rfc::RFC_GENERIC);
        $this->assertTrue($rfc->isGeneric());
        $this->assertFalse($rfc->isForeign());
        $this->assertFalse($rfc->isMoral());
        $this->assertTrue($rfc->isPerson());
    }

    public function testCreateDisallowGeneric(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('público en general');
        new Rfc(Rfc::RFC_GENERIC, Rfc::DISALLOW_GENERIC | Rfc::DISALLOW_FOREIGN);
    }

    public function testCreateDisallowForeign(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('operaciones con extranjeros');
        new Rfc(Rfc::RFC_FOREIGN, Rfc::DISALLOW_GENERIC | Rfc::DISALLOW_FOREIGN);
    }

    public function testCreateBadFormat(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('formato');
        new Rfc('COSC800113-7NA');
    }

    public function testCreateBadDate(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('fecha');
        new Rfc('COSC8002317NA');
    }

    public function testCreateBadDigit(): void
    {
        $rfc = new Rfc('COSC8001137N9');
        $this->assertSame('A', $rfc->checkSum());
        $this->assertFalse($rfc->checkSumMatch());
    }

    public function testIsValid(): void
    {
        $this->assertTrue(Rfc::isValid('COSC8001137NA'));
    }

    public function testIsNotValid(): void
    {
        $this->assertFalse(Rfc::isValid('COSC8099137NA'));
    }

    public function testWithMultiByte(): void
    {
        $rfcMultiByte = 'AÑÑ801231JK0';

        $expectedDate = strtotime('2080-12-31');
        $this->assertSame($expectedDate, Rfc::obtainDate($rfcMultiByte));

        $rfc = new Rfc($rfcMultiByte);
        $this->assertTrue($rfc->isMoral());
    }

    public function testObtainDateLeapYears(): void
    {
        // valid leap year
        $expectedDate = strtotime('2000-02-29');
        $this->assertSame($expectedDate, Rfc::obtainDate('XXX000229XX6'));

        // invalid leap year
        $this->assertSame(0, Rfc::obtainDate('XXX030229XX6'));
    }

    /**
     * @testWith [""]
     *           ["ABCD010100AAA"]
     *           ["ABCD010001AAA"]
     *           ["ABCD010132AAA"]
     *           ["ABCD010229AAA"]
     *           ["ABCD000230AAA"]
     *           ["ABCD0A0101AAA"]
     *           ["ABCD010A01AAA"]
     *           ["ABCD01010AAAA"]
     *           ["ABCD-10123AAA"]
     */
    public function testObtainDateWithInvalidInput(string $rfc): void
    {
        $this->assertSame(0, Rfc::obtainDate($rfc));
    }
}
