<?php

namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\Internals\TemporaryFile;
use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtilsTests\TestCase;

final class OpenSSLProtectedMethodCheckInputFileTest extends TestCase
{
    private function openSSL(): object
    {
        return new class () extends OpenSSL {
            public function checkInputFile(string $path): void
            {
                parent::checkInputFile($path);
                unset($path); // to avoid useless method overriding detected
            }
        };
    }

    public function testValidInputFile(): void
    {
        $this->openSSL()->checkInputFile(__FILE__);
        $this->assertTrue(true, 'No exception thrown'); /** @phpstan-ignore-line */
    }

    public function testThrowExceptionUsingEmptyFileName(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('argument is empty');
        $this->openSSL()->checkInputFile('');
    }

    public function testThrowExceptionUsingFileNonExistent(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('does not exists');
        $this->openSSL()->checkInputFile(__DIR__ . '/not-found');
    }

    public function testThrowExceptionUsingDirectory(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('is a directory');
        $this->openSSL()->checkInputFile(__DIR__);
    }

    public function testThrowExceptionUsingZeroFile(): void
    {
        $tempfile = TemporaryFile::create();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('is empty');
        try {
            $this->openSSL()->checkInputFile($tempfile);
        } finally {
            $tempfile->remove();
        }
    }
}
