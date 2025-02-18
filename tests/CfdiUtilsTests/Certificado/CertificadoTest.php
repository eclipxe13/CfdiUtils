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
            MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtmecO6n2GS0zL025gbHG
            QVxznPDICoXzR2uUngz4DqxVUC/w9cE6FxSiXm2ap8Gcjg7wmcZfm85EBaxCx/0J
            2u5CqnhzIoGCdhBPuhWQnIh5TLgj/X6uNquwZkKChbNe9aeFirU/JbyN7Egia9oK
            H9KZUsodiM/pWAH00PCtoKJ9OBcSHMq8Rqa3KKoBcfkg1ZrgueffwRLws9yOcRWL
            b02sDOPzGIm/jEFicVYt2Hw1qdRE5xmTZ7AGG0UHs+unkGjpCVeJ+BEBn0JPLWVv
            DKHZAQMj6s5Bku35+d/MyATkpOPsGT/VTnsouxekDfikJD1f7A1ZpJbqDpkJnss3
            vQIDAQAB
            -----END PUBLIC KEY-----

            EOD;
        $cerfile = $this->utilAsset('certs/EKU9003173C9.cer');

        $certificado = new Certificado($cerfile);

        $this->assertSame($cerfile, $certificado->getFilename());
        $certificateName = implode('', [
            '/CN=ESCUELA KEMPER URGATE SA DE CV',
            '/name=ESCUELA KEMPER URGATE SA DE CV',
            '/O=ESCUELA KEMPER URGATE SA DE CV',
            '/x500UniqueIdentifier=EKU9003173C9 / VADA800927DJ3',
            '/serialNumber= / VADA800927HSRSRL05',
            '/OU=Sucursal 1',
        ]);
        $this->assertSame($certificateName, str_replace('\/', '/', $certificado->getCertificateName()));
        $this->assertSame('ESCUELA KEMPER URGATE SA DE CV', $certificado->getName());
        $this->assertSame('ESCUELA KEMPER URGATE', $certificado->getName(true));
        $this->assertSame('EKU9003173C9', $certificado->getRfc());
        $this->assertSame('30001000000500003416', $certificado->getSerial());
        $this->assertSame(
            '3330303031303030303030353030303033343136',
            $certificado->getSerialObject()->getHexadecimal()
        );
        $this->assertSame(strtotime('2023-05-18T11:43:51+00:00'), $certificado->getValidFrom());
        $this->assertSame(strtotime('2027-05-18T11:43:51+00:00'), $certificado->getValidTo());
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
            $this->assertSame('30001000000500003416', $certificate->getSerial());
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
