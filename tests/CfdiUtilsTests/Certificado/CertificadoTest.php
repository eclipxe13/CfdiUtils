<?php

namespace CfdiUtilsTests\Certificado;

use CfdiUtils\Certificado\Certificado;
use CfdiUtilsTests\TestCase;

final class CertificadoTest extends TestCase
{
    public function testConstructWithValidExample()
    {
        // information checked using
        // openssl x509 -nameopt utf8,sep_multiline,lname -inform DER -noout -dates -serial -subject \
        //         -fingerprint -pubkey -in tests/assets/certs/CSD01_AAA010101AAA.cer
        $expectedPublicKey = <<< EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAl1RywcgQiDCK+8Bqe0ad
hUg7f7vJN0PW2Qqilsv60pKNEWjUSs90YnE/eDFPk74AIgNBc34dL24xYNidpRFq
VIgX0I4UJ2H84fY+f5SaQ3hy6WvYNqcrO1Ug7yJ1czpz1oefmE6juEPFcwLe464Z
XcVLg5uTFNX702y84BXaUx7btIetIHQOG4u6tRtnBeb7+vh23Pdva4uAFz4OLL3k
8b4wi7ug0ozz1oE0ZyNMnD72T5bMmI93dmlCAh51gEP7xBbGVgKDGGe1reT3KWBc
phcJiTSTSpq68EnWNJFV/kGOMhRs6y1pCnn2eSAeHjlz2CfAlFXrXw1bR8x4PO4p
HQIDAQAB
-----END PUBLIC KEY-----

EOD;
        $cerfile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer');

        $certificado = new Certificado($cerfile);

        $this->assertSame($cerfile, $certificado->getFilename());
        $certificateName = implode('', [
            '/CN=ACCEM SERVICIOS EMPRESARIALES SC',
            '/name=ACCEM SERVICIOS EMPRESARIALES SC',
            '/O=ACCEM SERVICIOS EMPRESARIALES SC',
            '/x500UniqueIdentifier=AAA010101AAA / HEGT7610034S2',
            '/serialNumber= / HEGT761003MDFRNN09',
            '/OU=CSD01_AAA010101AAA',
        ]);
        $this->assertSame($certificateName, $certificado->getCertificateName());
        $this->assertSame('ACCEM SERVICIOS EMPRESARIALES SC', $certificado->getName());
        $this->assertSame('AAA010101AAA', $certificado->getRfc());
        $this->assertSame('30001000000300023708', $certificado->getSerial());
        $this->assertSame(
            '3330303031303030303030333030303233373038',
            $certificado->getSerialObject()->getHexadecimal()
        );
        $this->assertSame(strtotime('2017-05-18T03:54:56+00:00'), $certificado->getValidFrom());
        $this->assertSame(strtotime('2021-05-18T03:54:56+00:00'), $certificado->getValidTo());
        $this->assertSame($expectedPublicKey, $certificado->getPubkey());
    }

    public function testVerifyWithKnownData()
    {
        $dataFile = $this->utilAsset('certs/data-to-sign.txt');
        $signatureFile = $this->utilAsset('certs/data-sha256.bin');
        $certificadoFile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer');

        $certificado = new Certificado($certificadoFile);
        $verify = $certificado->verify(
            str_replace("\r\n", "\n", strval(file_get_contents($dataFile))),
            strval(file_get_contents($signatureFile))
        );

        $this->assertTrue($verify);
    }

    public function testConstructUsingPemContents()
    {
        $pemfile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer.pem');
        $contents = file_get_contents($pemfile) ?: '';

        $fromFile = new Certificado($pemfile);
        $fromContents = new Certificado($contents);

        $this->assertSame($fromFile->getPemContents(), $fromContents->getPemContents());
    }

    public function testVerifyWithInvalidData()
    {
        $dataFile = $this->utilAsset('certs/data-to-sign.txt');
        $signatureFile = $this->utilAsset('certs/data-sha256.bin');
        $certificadoFile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer');

        $certificado = new Certificado($certificadoFile);
        $verify = $certificado->verify(
            strval(file_get_contents($dataFile)) . 'THIS IS MORE CONTENT!',
            strval(file_get_contents($signatureFile))
        );

        $this->assertFalse($verify);
    }

    public function testConstructWithUnreadableFile()
    {
        $badCertificateFile = $this->utilAsset('');

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('does not exists');

        new Certificado($badCertificateFile);
    }

    public function testConstructWithEmptyFile()
    {
        $badCertificateFile = $this->utilAsset('empty.bin');

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('empty');

        new Certificado($badCertificateFile);
    }

    public function testConstructWithNonExistentFile()
    {
        $badCertificateFile = $this->utilAsset('file-does-not-exists');

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('does not exists or is not readable');

        new Certificado($badCertificateFile);
    }

    public function testConstructWithBadCertificate()
    {
        $badCertificateFile = $this->utilAsset('certs/certificate-with-error.pem');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot parse the certificate file');

        new Certificado($badCertificateFile);
    }

    public function testConstructCertificateUsingPathThatIsBase64()
    {
        $workingdir = $this->utilAsset('certs/');
        $previousPath = getcwd();
        chdir($workingdir);
        try {
            $certificate = new Certificado('00001000000403258748.cer');
            $this->assertSame('00001000000403258748', $certificate->getSerial());
        } finally {
            chdir($previousPath);
        }
    }

    public function testConstructWithDerCertificateContentsThrowsException()
    {
        $file = $this->utilAsset('certs/CSD01_AAA010101AAA.cer');
        $this->expectException(\UnexpectedValueException::class);
        new Certificado(file_get_contents($file) ?: '');
    }

    public function testBelogsToReturnsTrueWithItsCertificate()
    {
        $certificateFile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer');
        $pemKeyFile = $this->utilAsset('certs/CSD01_AAA010101AAA.key.pem');
        $certificate = new Certificado($certificateFile);
        $this->assertTrue($certificate->belongsTo($pemKeyFile));
    }

    public function testBelogsToReturnsFalseWithOtherKey()
    {
        // the cer file is different from previous test
        $certificateFile = $this->utilAsset('certs/CSD09_AAA010101AAA.cer');
        $pemKeyFile = $this->utilAsset('certs/CSD01_AAA010101AAA.key.pem');
        $certificate = new Certificado($certificateFile);
        $this->assertFalse($certificate->belongsTo($pemKeyFile));
    }

    public function testBelongsToWithPasswordProtectedFile()
    {
        $certificateFile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer');
        $pemKeyFile = $this->utilAsset('certs/CSD01_AAA010101AAA_password.key.pem');
        $certificate = new Certificado($certificateFile);

        $this->assertTrue($certificate->belongsTo($pemKeyFile, '12345678a'));
    }

    public function testBelongsToWithPasswordProtectedFileButWrongPassword()
    {
        $certificateFile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer');
        $pemKeyFile = $this->utilAsset('certs/CSD01_AAA010101AAA_password.key.pem');
        $certificate = new Certificado($certificateFile);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot open the private key file');

        $certificate->belongsTo($pemKeyFile, 'xxxxxxxxx');
    }

    public function testBelongsToWithEmptyFile()
    {
        $certificateFile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer');
        $pemKeyFile = $this->utilAsset('empty.bin');
        $certificate = new Certificado($certificateFile);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('is not a PEM private key');

        $certificate->belongsTo($pemKeyFile);
    }

    public function testCanReadRfcFromCertificateWhenX500UniqueIdentifierOnlyContainsRfcAndNoCurp()
    {
        $certificateFile = $this->utilAsset('certs/00001000000301246267.cer');
        $certificate = new Certificado($certificateFile);
        $this->assertEquals('SOMG790807J57', $certificate->getRfc());
    }

    public function testGetSerialObjectReturnsACopyOfTheObjectInsteadTheSameObject()
    {
        // remove this test on version 3 when the object SerialNumber is immutable
        $certificateFile = $this->utilAsset('certs/00001000000301246267.cer');
        $certificate = new Certificado($certificateFile);

        $first = $certificate->getSerialObject();
        $second = $certificate->getSerialObject();
        $this->assertEquals($first, $second);
        $this->assertNotSame($first, $second);
    }
}
