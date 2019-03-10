<?php
namespace CfdiUtilsTests\OpenSSL;

/**
 * This test consider the file CSD01_AAA010101AAA.key.pem as an unprotected key file converted using command:
 * openssl pkcs8 -inform DER -passin pass:12345678a -in CSD01_AAA010101AAA.key -out CSD01_AAA010101AAA.key.pem
 *
 * Is a control test to ensure that PRIVATE KEY is working
 */
class PrivateKeyPredefinedTest extends PrivateKeyGenericTestCase
{
    protected function getPrivateKey(): string
    {
        $derkey = $this->utilAsset('certs/CSD01_AAA010101AAA.key.pem');
        return strval(file_get_contents($derkey));
    }
}
