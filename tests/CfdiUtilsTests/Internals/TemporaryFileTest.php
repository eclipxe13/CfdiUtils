<?php

namespace CfdiUtilsTests\Internals;

use CfdiUtils\Internals\TemporaryFile;
use CfdiUtilsTests\TestCase;

final class TemporaryFileTest extends TestCase
{
    public function testBasicFunctionality(): void
    {
        $temp = TemporaryFile::create();
        $this->assertFileExists($temp->getPath());
        $temp->remove();
        $this->assertFileDoesNotExist($temp->getPath());
    }

    public function testCreateWithDirectory(): void
    {
        $temp = TemporaryFile::create(__DIR__);
        try {
            $path = $temp->getPath();
            $this->assertSame(__DIR__, dirname($path));
            $this->assertFileExists($path);
        } finally {
            $temp->remove(); // cleanup
        }
    }

    public function testCreateOnNonExistentFolderThrowsException(): void
    {
        $directory = __DIR__ . '/non/existent/directory/';
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to create a temporary file');
        TemporaryFile::create($directory);
    }

    public function testCreateOnReadOnlyDirectoryThrowsException(): void
    {
        // prepare directory
        $directory = __DIR__ . '/readonly';
        mkdir($directory, 0500);

        // skip if it was not writable
        if (is_writable($directory)) {
            rmdir($directory);
            $this->markTestSkipped('Cannot create a read-only directory');
        }

        // setup expected exception
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to create a temporary file');

        // enclose on try {} finally {} for directory clean up
        try {
            TemporaryFile::create($directory);
        } finally {
            // clean up
            chmod($directory, 0777);
            rmdir($directory);
        }
    }

    public function testObjectToStringBehavior(): void
    {
        $file = TemporaryFile::create();
        $this->assertSame($file->getPath(), (string) $file);
        $file->remove();
    }

    public function testReadAndWrite(): void
    {
        $content = 'Lorem Ipsum';
        $file = TemporaryFile::create();
        $this->assertSame('', $file->retriveContents(), 'Contents should be empty');

        $file->storeContents($content);
        $this->assertSame($content, $file->retriveContents(), 'Contents should be what we have store');

        $file->remove();
    }

    public function testRunAndRemoveGreenPath(): void
    {
        $file = TemporaryFile::create();
        $expected = 'foo';

        $retrieved = $file->runAndRemove(fn (): string => $expected);

        $this->assertSame($expected, $retrieved, 'Method did not return the expected value');
        $this->assertFileDoesNotExist($file->getPath());
    }

    public function testRunAndRemoveWithException(): void
    {
        $file = TemporaryFile::create();

        try {
            $file->runAndRemove(function (): void {
                throw new \RuntimeException('DUMMY');
            });
        } catch (\RuntimeException $exception) {
            if ('DUMMY' !== $exception->getMessage()) {
                throw new \RuntimeException('Expected exception was not thrown', 0, $exception);
            }
        }

        $this->assertFileDoesNotExist($file->getPath());
    }
}
