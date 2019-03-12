<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtils\Utils\Internal\TemporaryFile;
use CfdiUtilsTests\TestCase;

class OpenSSLProtectedMethodCheckInputFileTest extends TestCase
{
    private function openSSL()
    {
        return new class() extends OpenSSL {
            public function checkInputFile(string $path)
            {
                parent::checkInputFile($path);
                unset($path); // to avoid useless method overriding detected
            }
        };
    }

    public function testValidInputFile()
    {
        $this->openSSL()->checkInputFile(__FILE__);
        $this->assertTrue(true, 'No exception thrown');
    }

    public function testThrowExceptionUsingFileNonExistent()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('does not exists');
        $this->openSSL()->checkInputFile(__DIR__ . '/not-found');
    }

    public function testThrowExceptionUsingDirectory()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('is a directory');
        $this->openSSL()->checkInputFile(__DIR__);
    }

    public function testThrowExceptionUsingZeroFile()
    {
        $tempfile = TemporaryFile::create();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('is empty');
        try {
            $this->openSSL()->checkInputFile($tempfile->getPath());
        } finally {
            $tempfile->remove();
        }
    }
}
