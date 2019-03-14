<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\Caller;
use CfdiUtils\OpenSSL\OpenSSLCallerException;
use CfdiUtils\OpenSSL\OpenSSLException;
use CfdiUtils\Utils\Internal\ShellExec;
use CfdiUtils\Utils\Internal\ShellExecResult;
use CfdiUtilsTests\TestCase;
use CfdiUtilsTests\Utils\Internal\FakeShellExec;

class CallerTest extends TestCase
{
    public function testConstructWithoutArguments()
    {
        $caller = new Caller();
        $this->assertSame(Caller::DEFAULT_OPENSSL_EXECUTABLE, $caller->getExecutable());
    }

    public function testConstructWithExecutableName()
    {
        $caller = new Caller('my-openssl');
        $this->assertSame('my-openssl', $caller->getExecutable());
    }

    public function testCallerWithNullCharacterOnTemplate()
    {
        $caller = new Caller();
        $this->expectException(OpenSSLException::class);
        $caller->call('?', ["\0"]);
    }

    public function testCallerWithNullCharacterOnEnvironment()
    {
        $caller = new Caller();
        $this->expectException(OpenSSLException::class);
        $caller->call('', [], ['env' => "\0"]);
    }

    public function testRunUsingMockedShellExecExpectingError()
    {
        $caller = new class() extends Caller {
            // change method visibility
            public function createShellExec(array $command, array $environment): ShellExec
            {
                $result = new ShellExecResult('command', 15, 'output', 'errors');
                return new FakeShellExec($command, $environment, $result);
            }
        };

        try {
            $caller->call('foo', []);
            $this->fail('Test did not throw a OpenSSLCallerException');
        } catch (OpenSSLCallerException $exception) {
            $callResponse = $exception->getCallResponse();
            $this->assertSame(15, $callResponse->exitStatus());
            $this->assertSame('command', $callResponse->commandLine());
            $this->assertSame('output', $callResponse->output());
            $this->assertSame('errors', $callResponse->errors());
        }
    }

    public function testRunUsingMockedShellExecExpectingSuccess()
    {
        $caller = new class() extends Caller {
            public function createShellExec(array $command, array $environment): ShellExec
            {
                $result = new ShellExecResult('command', 0, 'output', 'errors');
                return new FakeShellExec($command, $environment, $result);
            }
        };

        $callResponse = $caller->call('foo', []);
        $this->assertSame(0, $callResponse->exitStatus());
        $this->assertSame('command', $callResponse->commandLine());
        $this->assertSame('output', $callResponse->output());
        $this->assertSame('errors', $callResponse->errors());
    }
}
