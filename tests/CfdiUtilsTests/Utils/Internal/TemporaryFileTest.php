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
}
