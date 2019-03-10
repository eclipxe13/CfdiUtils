<?php
namespace CfdiUtilsTests\OpenSSL;

/**
 * This test convert the file CSD01_AAA010101AAA.key as an unprotected key file converted using command:
 * openssl pkcs8 -inform DER -passin pass:12345678a -in CSD01_AAA010101AAA.key -out CSD01_AAA010101AAA.key.pem
 *
 * Is a control test to ensure that PRIVATE KEY is working
 */
class PrivateKeyConvertedTest extends PrivateKeyGenericTestCase
{
    protected function getPrivateKey(): string
    {
        $derkeyFile = $this->utilAsset('certs/CSD01_AAA010101AAA.key');
        $derkey = strval(file_get_contents($derkeyFile));
        $openSSL = $this->getOpenSSL();
        $pemkey = $openSSL->convertPrivateKeyContentsDERToPEM($derkey, '12345678a');
        return $pemkey;
    }
}
