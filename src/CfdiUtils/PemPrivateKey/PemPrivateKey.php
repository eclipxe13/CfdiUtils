<?php
namespace CfdiUtils\PemPrivateKey;

class PemPrivateKey
{
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
     * @throws \UnexpectedValueException if the file is not PEM format
     */
    public function __construct(string $key)
    {
        if (0 === strpos($key, 'file://')) {
            $contents = '';
            $filename = substr($key, 7);
            if ('' !== $filename && file_exists($filename)) {
                $contents = file_get_contents($filename);
            }
        } else {
            $contents = $key;
        }
        if (! $this->isPEM($contents)) {
            throw new \UnexpectedValueException('The key is not a file or a string PEM format private key');
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

    public function isOpened(): bool
    {
        return (null !== $this->privatekey);
    }

    /** @return resource|null */
    private function getOpenedPrivateKey()
    {
        if (null === $this->privatekey) {
            throw new \RuntimeException('The private key is not opened');
        }
        return $this->privatekey;
    }

    public function sign(string $data, int $algorithm = OPENSSL_ALGO_SHA256): string
    {
        if (false === openssl_sign($data, $signature, $this->getOpenedPrivateKey(), $algorithm)) {
            $signature = '';
        }
        if ('' === $signature) {
            throw new \RuntimeException('Cannot create the sign data');
        }
        return $signature;
    }

    public function belongsTo(string $pemContents): bool
    {
        return openssl_x509_check_private_key($pemContents, $this->getOpenedPrivateKey());
    }

    /**
     * Check if a string has an obvious signature of a PEM file
     * @param string $keyContents
     * @return bool
     */
    public static function isPEM(string $keyContents): bool
    {
        $keyContents = rtrim($keyContents);
        $templates = [
            '-----%s PRIVATE KEY-----',
            '-----%s RSA PRIVATE KEY-----',
        ];
        if (! self::isPEMHasHeader($keyContents, $templates)) {
            return false;
        }
        if (! self::isPEMHasFooter($keyContents, $templates)) {
            return false;
        }
        return true;
    }

    private static function isPEMHasHeader(string $keyContents, array $templates): bool
    {
        foreach ($templates as $template) {
            if (0 === strpos($keyContents, sprintf($template, 'BEGIN'))) {
                return true;
            }
        }
        return false;
    }

    private static function isPEMHasFooter(string $keyContents, array $templates): bool
    {
        foreach ($templates as $template) {
            $search = sprintf($template, 'END');
            if ($search === substr($keyContents, - strlen($search))) {
                return true;
            }
        }
        return false;
    }
}
