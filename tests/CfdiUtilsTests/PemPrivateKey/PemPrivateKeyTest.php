<?php

namespace CfdiUtilsTests\PemPrivateKey;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use CfdiUtilsTests\TestCase;

final class PemPrivateKeyTest extends TestCase
{
    public function providerConstructWithBadArgument(): array
    {
        return [
            'empty' => [''],
            'random content' => ['foo bar'],
            'file://' => ['file://'],
            'file without prefix' => [__FILE__],
            'non-existent-file' => ['file://' . __DIR__ . '/non-existent-file'],
            'existent but is a directory' => ['file://' . __DIR__],
            'existent but invalid file' => ['file://' . __FILE__],
            'cer file' => ['file://' . static::utilAsset('certs/EKU9003173C9.cer')],
            'key not pem file' => ['file://' . static::utilAsset('certs/EKU9003173C9.key')],
            'no footer' => ['-----BEGIN PRIVATE KEY-----XXXXX'],
            'hidden url' => ['file://https://cdn.kernel.org/pub/linux/kernel/v4.x/linux-4.13.9.tar.xz'],
        ];
    }

    /**
     * @param string $key
     * @dataProvider providerConstructWithBadArgument
     */
    public function testConstructWithBadArgument(string $key)
    {
        $this->expectException(\UnexpectedValueException::class);
        new PemPrivateKey($key);
    }

    public function testConstructWithKeyFile()
    {
        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $privateKey = new PemPrivateKey('file://' . $keyfile);
        $this->assertInstanceOf(PemPrivateKey::class, $privateKey);
    }

    public function testConstructWithKeyContents()
    {
        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $privateKey = new PemPrivateKey(strval(file_get_contents($keyfile)));
        $this->assertInstanceOf(PemPrivateKey::class, $privateKey);
    }

    public function testOpenAndClose()
    {
        $passPhrase = '';
        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $privateKey = new PemPrivateKey(strval(file_get_contents($keyfile)));
        $this->assertFalse($privateKey->isOpen());
        $this->assertTrue($privateKey->open($passPhrase));
        $this->assertTrue($privateKey->isOpen());
        $privateKey->close();
        $this->assertFalse($privateKey->isOpen());
    }

    public function testOpenWithBadKey()
    {
        $keyContents = "-----BEGIN PRIVATE KEY-----\nXXXXX\n-----END PRIVATE KEY-----";
        $privateKey = new PemPrivateKey($keyContents);
        $this->assertFalse($privateKey->open(''));
    }

    public function testOpenWithIncorrectPassPhrase()
    {
        $passPhrase = 'dummy password';
        $keyfile = $this->utilAsset('certs/EKU9003173C9_password.key.pem');
        $privateKey = new PemPrivateKey(strval(file_get_contents($keyfile)));
        $this->assertFalse($privateKey->open($passPhrase));
        $this->assertFalse($privateKey->isOpen());
    }

    public function testOpenWithCorrectPassPhrase()
    {
        $passPhrase = '12345678a';
        $keyfile = $this->utilAsset('certs/EKU9003173C9_password.key.pem');
        $privateKey = new PemPrivateKey(strval(file_get_contents($keyfile)));
        $this->assertTrue($privateKey->open($passPhrase));
        $this->assertTrue($privateKey->isOpen());
    }

    public function testCloneOpenKey()
    {
        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $privateKey = new PemPrivateKey(strval(file_get_contents($keyfile)));
        $this->assertTrue($privateKey->open(''));

        $cloned = clone $privateKey;
        $this->assertFalse($cloned->isOpen());
        $this->assertTrue($cloned->open(''));
    }

    public function testSerializeOpenKey()
    {
        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $privateKey = new PemPrivateKey(strval(file_get_contents($keyfile)));
        $this->assertTrue($privateKey->open(''));

        /** @var PemPrivateKey $serialized */
        $serialized = unserialize(serialize($privateKey));
        $this->assertFalse($serialized->isOpen());
        $this->assertTrue($serialized->open(''));
    }

    public function testSignWithClosedKey()
    {
        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $privateKey = new PemPrivateKey(strval(file_get_contents($keyfile)));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The private key is not open');
        $privateKey->sign('');
    }

    public function testSign()
    {
        // this signature was createrd using the following command:
        // echo -n lorem ipsum | openssl dgst -sha256 -sign EKU9003173C9.key.pem -out - | base64 -w 80

        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $privateKey = new PemPrivateKey(strval(file_get_contents($keyfile)));
        $privateKey->open('');

        $content = 'lorem ipsum';
        $expectedSign = <<< EOC
CzjYgB2dOp4P76kYBSGymRJdQo9hjErCF+5mvoiVWVnvcV/eg9IkW+1DnOem5slYzU9+lzOo+I79wcOe
0gRtsmybGnViXxAQ8rr7YciFCoyqtKhxGjdgBpvO2NMT84n6U8ChYb8v7O/s4Gi5yTPj9D113rNsQGb8
5nXerA+N6G6axy0F/IcUMZ6VPkDDjATcwjEj5A3q7qORG/l2cAKaV4nGKjn8V82bZ40ys7PGvFfZfirZ
BeKg1QPUqf2fpgVI6wf/IM4YRD6ZbTgtFiYH30/dlzowZTAR1NMHJXa4uxCdTY7mQVekTw0FNDxrAZr/
5lLezLMMyEezIz+EQKgAvg==
EOC;
        $sign = chunk_split(base64_encode($privateKey->sign($content, OPENSSL_ALGO_SHA256)), 80, "\n");
        $this->assertEquals($expectedSign, rtrim($sign));
    }

    public function testBelongsToWithClosedKey()
    {
        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $privateKey = new PemPrivateKey(strval(file_get_contents($keyfile)));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The private key is not open');
        $privateKey->belongsTo('');
    }

    public function testBelongsTo()
    {
        $cerfile = $this->utilAsset('certs/EKU9003173C9.cer');
        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $privateKey = new PemPrivateKey(strval(file_get_contents($keyfile)));
        $privateKey->open('');
        $certificado = new Certificado($cerfile);
        $this->assertTrue($privateKey->belongsTo($certificado->getPemContents()));
    }
}
