<?php
namespace CfdiUtilsTests\Certificado;

use CfdiUtils\Certificado\SatCertificateNumber;
use CfdiUtilsTests\TestCase;

class SatCertificateNumberTest extends TestCase
{
    public function providerValidNumbers()
    {
        return [
            ['00000000000000000000'],
            ['98765432109876543210'],
        ];
    }

    public function providerInvalidNumbers()
    {
        return [
            'empty' => [''],
            'with-non-digits' => ['A0000000000000000000'],
            'length 19' => ['0000000000000000000'],
            'length 21' => ['000000000000000000000'],
        ];
    }

    /**
     * @param $value
     * @dataProvider providerValidNumbers
     */
    public function testIsValidCertificateNumberWithCorrectValues($value)
    {
        $this->assertSame(true, SatCertificateNumber::isValidCertificateNumber($value));
        $number = new SatCertificateNumber($value);
        $this->assertSame($value, $number->number());
        $this->assertStringEndsWith($value . '.cer', $number->remoteUrl());
    }

    /**
     * @param $value
     * @dataProvider providerInvalidNumbers
     */
    public function testIsValidCertificateNumberWithIncorrectValues($value)
    {
        $this->assertSame(false, SatCertificateNumber::isValidCertificateNumber($value));

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The certificate number is not correct');

        new SatCertificateNumber($value);
    }
}
