<?php
namespace CfdiUtilsTests\Utils\Internal;

use CfdiUtils\Utils\Internal\TemporaryFile;
use CfdiUtilsTests\TestCase;

class TemporaryFileTest extends TestCase
{
    public function testBasicFunctionality()
    {
        $temp = TemporaryFile::create();
        $this->assertFileExists($temp->getPath());
        $temp->remove();
        $this->assertFileNotExists($temp->getPath());
    }

    public function testCreateWithDirectory()
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

    public function testCreateOnNonExistentFolderThrowsException()
    {
        $directory = __DIR__ . '/non/existent/directory/';
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to create a temporary file');
        TemporaryFile::create($directory);
    }

    public function testCreateOnReadOnlyFolderThrowsException()
    {
        // redirect test to MS Windows case
        if ($this->isRunningOnWindows()) {
            // don't know how to test this on AppVeyor
            // since MS Windows create file on folder with chmod 0400
            // and AppVeyor allow write on WINDIR
            $this->markTestSkipped('Cannot create scenario to perform this test on MS Windows');
            return;
        }

        // prepare directory
        $directory = __DIR__ . '/readonly';
        mkdir($directory);
        chmod($directory, 0500);

        // setup exception
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to create a temporary file');
        try {
            TemporaryFile::create($directory);
        } finally {
            // cleanup
            chmod($directory, 0700);
            rmdir($directory);
        }
    }

    public function testObjectToStringBehavior()
    {
        $file = TemporaryFile::create();
        $this->assertSame($file->getPath(), (string) $file);
        $file->remove();
    }

    public function testReadAndWrite()
    {
        $content = 'Lorem Ipsum';
        $file = TemporaryFile::create();
        $this->assertSame('', $file->retriveContents(), 'Contents should be empty');

        $file->storeContents($content);
        $this->assertSame($content, $file->retriveContents(), 'Contents should be what we have store');

        $file->remove();
    }

    public function testRunAndRemoveGreenPath()
    {
        $file = TemporaryFile::create();
        $expected = 'foo';

        $retrieved = $file->runAndRemove(function () use ($expected) {
            return $expected;
        });

        $this->assertSame($expected, $retrieved, 'Method did not return the expected value');
        $this->assertFileNotExists($file->getPath());
    }

    public function testRunAndRemoveWithException()
    {
        $file = TemporaryFile::create();

        try {
            $file->runAndRemove(function () {
                throw new \RuntimeException('DUMMY');
            });
        } catch (\RuntimeException $exception) {
            if ('DUMMY' !== $exception->getMessage()) {
                throw new \RuntimeException('Expected exception was not thrown', 0, $exception);
            }
        }

        $this->assertFileNotExists($file->getPath());
    }
}
