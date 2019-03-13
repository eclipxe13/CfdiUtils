<?php
namespace CfdiUtilsTests\Utils\Internal;

use CfdiUtils\Utils\Internal\ShellExec;
use PHPUnit\Framework\TestCase;

class ShellExecTest extends TestCase
{
    public function testConstructWithNoCommand()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Command definition is empty');
        new ShellExec([]);
    }

    public function testConstructWithEmptyFirstCommandArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Command executable is empty');
        new ShellExec(['']);
    }

    public function testConstructWithValues()
    {
        $command = ['foo'];
        $environment = ['KEY' => 'value'];
        $shellExec = new ShellExec($command, $environment);

        $this->assertSame($command, $shellExec->getCommand());
        $this->assertSame($environment, $shellExec->getEnvironment());
    }

    public function testRunGenericOperatingSystemCommand()
    {
        $command = ['dir', '%s', __FILE__];
        $execution = (new ShellExec($command))->run();

        $expectedContent = basename(__FILE__);
        $this->assertContains($expectedContent, $execution->output());
    }

    public function testRunExpectingExitStatus()
    {
        $command = [PHP_BINARY, '-r', 'exit(8);'];

        $execution = (new ShellExec($command))->run();

        $this->assertSame(8, $execution->exitStatus());
    }

    public function testCaptureOutputAndErrors()
    {
        $printer = implode(PHP_EOL, [
            'file_put_contents("php://stderr", "Line sent to STDERR", FILE_APPEND);',
            'file_put_contents("php://stdout", "Line sent to STDOUT", FILE_APPEND);',
        ]);
        $command = [PHP_BINARY, '-r', $printer];

        $execution = (new ShellExec($command))->run();

        $this->assertSame('Line sent to STDOUT', $execution->output());
        $this->assertSame('Line sent to STDERR', $execution->errors());
    }

    public function testStdinDoesNotLockProcess()
    {
        $printer = implode(PHP_EOL, [
            'fgets(STDIN);',
            'file_put_contents("php://stdout", "BYE", FILE_APPEND);',
            'exit(2);',
        ]);
        $command = [PHP_BINARY, '-r', $printer];

        $execution = (new ShellExec($command))->run();

        $this->assertSame('BYE', $execution->output());
        $this->assertSame(2, $execution->exitStatus());
    }

    public function testCanSendEnvironment()
    {
        $printer = 'echo getenv("FOO"), " / ", getenv("BAR");';
        $command = [PHP_BINARY, '-r', $printer];

        $environment = [
            'FOO' => 'f o o',
            'BAR' => 'b a r',
        ];
        $execution = (new ShellExec($command, $environment))->run();

        $this->assertSame('f o o / b a r', $execution->output());
    }
}
