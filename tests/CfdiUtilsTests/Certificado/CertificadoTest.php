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
        //         -fingerprint -pubkey -in tests/assets/certs/EKU9003173C9.cer
        $expectedPublicKey = <<< EOD
            -----BEGIN PUBLIC KEY-----
            MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjdKXiqYHzi++YmEb9X6q
            vqFWLCz1VEfxom2JhinPSJxxcuZWBejk2I5yCL5pDnUaG2xpQlMTkV/7S7JfGGvY
            JumKO4R5zg0QSA7qdxiEhcwf/ekfSvzM2EDnLHDCKAQwEWsnJy78uxZTLzu/65VZ
            7EgEcWUTvCs/GZJLI9s6XmKY2SMmv9+vfqBqkJNXE0ZB6OfSbyeE325P94iMn+B/
            yJ4vZwXvXGFqNDJyqG+ww7f77HYubQPJjLQPedy2qTcgmSAwkUEJVBjYA6mPf/Be
            ZlL1YJHHM7CIBnb3/bzED0n944woio+4+rnMZdfhcCVpm74DZomlEf9KuJtq5u/J
            RQIDAQAB
            -----END PUBLIC KEY-----

            EOD;
        $cerfile = $this->utilAsset('certs/EKU9003173C9.cer');

        $certificado = new Certificado($cerfile);

        $this->assertSame($cerfile, $certificado->getFilename());
        $certificateName = implode('', [
            '/CN=ESCUELA KEMPER URGATE SA DE CV',
            '/name=ESCUELA KEMPER URGATE SA DE CV',
            '/O=ESCUELA KEMPER URGATE SA DE CV',
            '/x500UniqueIdentifier=EKU9003173C9 / XIQB891116QE4',
            '/serialNumber= / XIQB891116MGRMZR05',
            '/OU=Escuela Kemper Urgate',
        ]);
        $this->assertSame($certificateName, $certificado->getCertificateName());
        $this->assertSame('ESCUELA KEMPER URGATE SA DE CV', $certificado->getName());
        $this->assertSame('EKU9003173C9', $certificado->getRfc());
        $this->assertSame('30001000000400002434', $certificado->getSerial());
        $this->assertSame(
            '3330303031303030303030343030303032343334',
            $certificado->getSerialObject()->getHexadecimal()
        );
        $this->assertSame(strtotime('2019-06-17T14:44:14-05:00'), $certificado->getValidFrom());
        $this->assertSame(strtotime('2023-06-17T14:44:14-05:00'), $certificado->getValidTo());
        $this->assertSame($expectedPublicKey, $certificado->getPubkey());
    }

    public function testVerifyWithKnownData()
    {
        $dataFile = $this->utilAsset('certs/data-to-sign.txt');
        $signatureFile = $this->utilAsset('certs/data-sha256.bin');
        $certificadoFile = $this->utilAsset('certs/EKU9003173C9.cer');

        $certificado = new Certificado($certificadoFile);
        $verify = $certificado->verify(
            str_replace("\r\n", "\n", strval(file_get_contents($dataFile))),
            strval(file_get_contents($signatureFile))
        );

        $this->assertTrue($verify);
    }

    public function testConstructUsingPemContents()
    {
        $pemfile = $this->utilAsset('certs/EKU9003173C9.cer.pem');
        $contents = file_get_contents($pemfile) ?: '';

        $fromFile = new Certificado($pemfile);
        $fromContents = new Certificado($contents);

        $this->assertSame($fromFile->getPemContents(), $fromContents->getPemContents());
    }

    public function testVerifyWithInvalidData()
    {
        $dataFile = $this->utilAsset('certs/data-to-sign.txt');
        $signatureFile = $this->utilAsset('certs/data-sha256.bin');
        $certificadoFile = $this->utilAsset('certs/EKU9003173C9.cer');

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
            $certificate = new Certificado('EKU9003173C9.cer');
            $this->assertSame('30001000000400002434', $certificate->getSerial());
        } finally {
            chdir($previousPath);
        }
    }

    public function testConstructWithDerCertificateContentsThrowsException()
    {
        $file = $this->utilAsset('certs/EKU9003173C9.cer');
        $this->expectException(\UnexpectedValueException::class);
        new Certificado(file_get_contents($file) ?: '');
    }

    public function testBelogsToReturnsTrueWithItsCertificate()
    {
        $certificateFile = $this->utilAsset('certs/EKU9003173C9.cer');
        $pemKeyFile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $certificate = new Certificado($certificateFile);
        $this->assertTrue($certificate->belongsTo($pemKeyFile));
    }

    public function testBelogsToReturnsFalseWithOtherKey()
    {
        // the cer file is different from previous test
        $certificateFile = $this->utilAsset('certs/CSD09_AAA010101AAA.cer');
        $pemKeyFile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $certificate = new Certificado($certificateFile);
        $this->assertFalse($certificate->belongsTo($pemKeyFile));
    }

    public function testBelongsToWithPasswordProtectedFile()
    {
        $certificateFile = $this->utilAsset('certs/EKU9003173C9.cer');
        $pemKeyFile = $this->utilAsset('certs/EKU9003173C9_password.key.pem');
        $certificate = new Certificado($certificateFile);

        $this->assertTrue($certificate->belongsTo($pemKeyFile, '12345678a'));
    }

    public function testBelongsToWithPasswordProtectedFileButWrongPassword()
    {
        $certificateFile = $this->utilAsset('certs/EKU9003173C9.cer');
        $pemKeyFile = $this->utilAsset('certs/EKU9003173C9_password.key.pem');
        $certificate = new Certificado($certificateFile);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot open the private key file');

        $certificate->belongsTo($pemKeyFile, 'xxxxxxxxx');
    }

    public function testBelongsToWithEmptyFile()
    {
        $certificateFile = $this->utilAsset('certs/EKU9003173C9.cer');
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
        $certificateFile = $this->utilAsset('certs/EKU9003173C9.cer');
        $certificate = new Certificado($certificateFile);

        $first = $certificate->getSerialObject();
        $second = $certificate->getSerialObject();
        $this->assertEquals($first, $second);
        $this->assertNotSame($first, $second);
    }
}
