<?php
namespace CfdiUtilsTests\Utils\Internal;

use CfdiUtils\Utils\Internal\ShellExec;
use PHPUnit\Framework\TestCase;

class ShellExecTest extends TestCase
{
    public function testRun()
    {
        $command = sprintf('dir %s', escapeshellcmd(__FILE__));
        $execution = ShellExec::run($command);

        $thisfile = basename(__FILE__);
        // $this->assertSame($command, $execution->command());
        $this->assertSame(0, $execution->exitStatus());
        $this->assertStringContainsString($thisfile, $execution->output());
    }

    public function testRunExpectingExitStatus()
    {
        $command = implode(' ', array_map('escapeshellarg', [PHP_BINARY, '-r', 'exit(8);']));

        $execution = ShellExec::run($command);

        $this->assertSame(8, $execution->exitStatus());
    }

    public function testCapturesStdoutStderr()
    {
        $printer = implode(PHP_EOL, [
            'file_put_contents("php://stdout", "Line sent to STDOUT", FILE_APPEND);',
            'file_put_contents("php://stderr", "Line sent to STDERR", FILE_APPEND);',
        ]);
        $command = implode(' ', array_map('escapeshellarg', [PHP_BINARY, '-r', $printer]));

        $execution = ShellExec::run($command);

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
        $command = implode(' ', array_map('escapeshellarg', [PHP_BINARY, '-r', $printer]));

        $execution = ShellExec::run($command);

        $this->assertSame('BYE', $execution->output());
        $this->assertSame(2, $execution->exitStatus());
    }

    public function testCanSendEnvinment()
    {
        $printer = implode(PHP_EOL, [
            'echo getenv("FOO"), " ", getenv("BAR");',
        ]);
        $command = implode(' ', array_map('escapeshellarg', [PHP_BINARY, '-r', $printer]));

        $environment = [
            'FOO' => 'foo',
            'BAR' => 'bar',
        ];
        $execution = ShellExec::run($command, $environment);

        $this->assertSame('foo bar', $execution->output());
    }
}
