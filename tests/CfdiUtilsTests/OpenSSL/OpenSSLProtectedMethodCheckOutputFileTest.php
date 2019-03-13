<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtils\Utils\Internal\TemporaryFile;
use CfdiUtilsTests\TestCase;

class OpenSSLProtectedMethodCheckOutputFileTest extends TestCase
{
    private function openSSL()
    {
        return new class() extends OpenSSL {
            public function checkOutputFile(string $path)
            {
                parent::checkOutputFile($path);
                unset($path); // to avoid useless method overriding detected
            }
        };
    }

    public function testValidOutputFileNonExistent()
    {
        $this->openSSL()->checkOutputFile(__DIR__ . '/non-existent');
        $this->assertTrue(true, 'No exception thrown');
    }

    public function testValidOutputFileZeroSize()
    {
        $tempfile = TemporaryFile::create();
        try {
            $this->openSSL()->checkOutputFile($tempfile->getPath());
        } finally {
            $tempfile->remove();
        }
        $this->assertTrue(true, 'No exception thrown');
    }

    public function testThrowExceptionUsingEmptyFileName()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('argument is empty');
        $this->openSSL()->checkOutputFile('');
    }

    public function testThrowExceptionUsingNonExistentParentDirectory()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('not exists');
        $this->openSSL()->checkOutputFile(__DIR__ . '/a/b/c');
    }

    public function testThrowExceptionUsingDirectory()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('is a directory');
        $this->openSSL()->checkOutputFile(__DIR__);
    }

    public function testThrowExceptionUsingZeroFile()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('not empty');
        $this->openSSL()->checkOutputFile(__FILE__);
    }
}
