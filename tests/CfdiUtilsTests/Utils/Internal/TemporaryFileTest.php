<?php
namespace CfdiUtilsTests\Utils\Internal;

use CfdiUtils\Utils\Internal\TemporaryFile;
use PHPUnit\Framework\TestCase;

class TemporaryFileTest extends TestCase
{
    public function testBasicFunctionality()
    {
        $temp = TemporaryFile::create();
        $this->assertFileExists($temp->getPath());
        $temp->remove();
        $this->assertFileNotExists($temp->getPath());
    }

    public function testCreateWithPrefix()
    {
        $prefix = 'prefix-';
        $temp = TemporaryFile::create($prefix);
        try {
            $path = $temp->getPath();
            $this->assertStringStartsWith($prefix, basename($path));
            $this->assertFileExists($path);
        } finally {
            $temp->remove(); // cleanup
        }
    }

    public function testCreateWithDirectory()
    {
        $temp = TemporaryFile::create('', __DIR__);
        try {
            $path = $temp->getPath();
            $this->assertSame(__DIR__, dirname($path));
            $this->assertFileExists($path);
        } finally {
            $temp->remove(); // cleanup
        }
    }

    public function testCreateWouldFailIfCannotCreateTheFile()
    {
        $directory = __DIR__ . '/non/existent/directory/';
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to create a temporary file');
        TemporaryFile::create('', $directory);
    }

    public function testCreateOnReadOnlyFolder()
    {
        $folder = __DIR__ . '/readonly';
        mkdir($folder);
        chmod($folder, 0550);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to create a temporary file');
        try {
            TemporaryFile::create('', $folder);
        } finally {
            rmdir($folder);
        }
    }
}
