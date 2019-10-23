<?php

namespace CfdiUtils\PemPrivateKey;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtils\OpenSSL\OpenSSLPropertyTrait;

class PemPrivateKey
{
    use OpenSSLPropertyTrait;

    /** @var string */
    private $contents;

    /** @var resource|null */
    private $privatekey;

    /**
     * Create a private key helper class based on a private key PEM formatted
     * The key argument can be:
     * - file location starting with 'file://'
     * - file contents
     *
     * @param string $key
     * @param \CfdiUtils\OpenSSL\OpenSSL $openSSL
     * @throws \UnexpectedValueException if the file is not PEM format
     */
    public function __construct(string $key, OpenSSL $openSSL = null)
    {
        $this->setOpenSSL($openSSL ?: new OpenSSL());
        try {
            if (0 === strpos($key, 'file://')) {
                $filename = substr($key, 7);
                $contents = $this->getOpenSSL()->readPemFile($filename)->privateKey();
            } else {
                $contents = $this->getOpenSSL()->readPemContents($key)->privateKey();
            }
            if ('' === $contents) {
                throw new \RuntimeException('Empty key');
            }
        } catch (\Throwable $exc) {
            throw new \UnexpectedValueException('The key is not a file or a string PEM format private key', 0, $exc);
        }

        $this->contents = $contents;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function __clone()
    {
        $this->privatekey = null;
    }

    public function __sleep()
    {
        return ['contents'];
    }

    public function open(string $passPhrase): bool
    {
        $this->close();
        $pKey = openssl_pkey_get_private($this->contents, $passPhrase);
        if (false === $pKey) {
            return false;
        }
        $this->privatekey = $pKey;
        return true;
    }

    public function close()
    {
        if (null !== $this->privatekey) {
            openssl_pkey_free($this->privatekey);
            $this->privatekey = null;
        }
    }

    /**
     * @see isOpen
     * @return bool
     * @deprecated :3.0.0 use isOpen instead
     */
    public function isOpened(): bool
    {
        return $this->isOpen();
    }

    public function isOpen(): bool
    {
        return (null !== $this->privatekey);
    }

    /** @return resource */
    private function getOpenPrivateKey()
    {
        if (! is_resource($this->privatekey)) {
            throw new \RuntimeException('The private key is not open');
        }
        return $this->privatekey;
    }

    public function sign(string $data, int $algorithm = OPENSSL_ALGO_SHA256): string
    {
        if (false === openssl_sign($data, $signature, $this->getOpenPrivateKey(), $algorithm)) {
            $signature = '';
        }
        if ('' === $signature) {
            throw new \RuntimeException('Cannot create the sign data');
        }
        return $signature;
    }

    public function belongsTo(string $pemContents): bool
    {
        return openssl_x509_check_private_key($pemContents, $this->getOpenPrivateKey());
    }

    /**
     * Check if a string has an obvious signature of a PEM file
     * @param string $keyContents
     * @return bool
     * @deprecated 2.9.0 Replaced with OpenSSL utility
     * @see OpenSSL
     */
    public static function isPEM(string $keyContents): bool
    {
        if ('' === $keyContents) {
            return false;
        }

        $openSSL = new OpenSSL();
        return $openSSL->readPemContents($keyContents)->hasPrivateKey();
    }
}
