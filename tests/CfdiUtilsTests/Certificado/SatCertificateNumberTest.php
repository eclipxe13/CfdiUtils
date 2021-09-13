<?php

namespace CfdiUtilsTests\Certificado;

use CfdiUtils\Certificado\SatCertificateNumber;
use CfdiUtilsTests\TestCase;

final class SatCertificateNumberTest extends TestCase
{
    public function providerValidNumbers(): array
    {
        return [
            ['00000000000000000000'],
            ['98765432109876543210'],
        ];
    }

    public function providerInvalidNumbers(): array
    {
        return [
            'empty' => [''],
            'with-non-digits' => ['A0000000000000000000'],
            'length 19' => ['0000000000000000000'],
            'length 21' => ['000000000000000000000'],
        ];
    }

    /**
     * @param string $value
     * @dataProvider providerValidNumbers
     */
    public function testIsValidCertificateNumberWithCorrectValues(string $value)
    {
        $this->assertSame(true, SatCertificateNumber::isValidCertificateNumber($value));
        $number = SatCertificateNumber::newFromString($value);
        $this->assertSame($value, $number->number());
        $this->assertStringEndsWith($value . '.cer', $number->remoteUrl());
    }

    /**
     * @param string $value
     * @dataProvider providerInvalidNumbers
     */
    public function testIsValidCertificateNumberWithIncorrectValues(string $value)
    {
        $this->assertSame(false, SatCertificateNumber::isValidCertificateNumber($value));

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The certificate number is not correct');

        SatCertificateNumber::newFromString($value);
    }
}
