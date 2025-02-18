<?php

namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\Internals\TemporaryFile;
use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtilsTests\TestCase;

final class OpenSSLProtectedMethodCheckOutputFileTest extends TestCase
{
    private function openSSL(): object
    {
        return new class () extends OpenSSL {
            public function checkOutputFile(string $path): void
            {
                parent::checkOutputFile($path);
                unset($path); // to avoid useless method overriding detected
            }
        };
    }

    public function testValidOutputFileNonExistent(): void
    {
        $this->openSSL()->checkOutputFile(__DIR__ . '/non-existent');
        $this->assertTrue(true, 'No exception thrown'); /** @phpstan-ignore-line */
    }

    public function testValidOutputFileZeroSize(): void
    {
        $tempfile = TemporaryFile::create();
        try {
            $this->openSSL()->checkOutputFile($tempfile);
        } finally {
            $tempfile->remove();
        }
        $this->assertTrue(true, 'No exception thrown'); /** @phpstan-ignore-line */
    }

    public function testThrowExceptionUsingEmptyFileName(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('argument is empty');
        $this->openSSL()->checkOutputFile('');
    }

    public function testThrowExceptionUsingNonExistentParentDirectory(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('not exists');
        $this->openSSL()->checkOutputFile(__DIR__ . '/a/b/c');
    }

    public function testThrowExceptionUsingDirectory(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('is a directory');
        $this->openSSL()->checkOutputFile(__DIR__);
    }

    public function testThrowExceptionUsingZeroFile(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('not empty');
        $this->openSSL()->checkOutputFile(__FILE__);
    }
}
