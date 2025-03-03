<?php

namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\Caller;
use CfdiUtils\OpenSSL\OpenSSLCallerException;
use CfdiUtils\OpenSSL\OpenSSLException;
use CfdiUtilsTests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Process\Process;

final class CallerTest extends TestCase
{
    public function testConstructWithoutArguments(): void
    {
        $caller = new Caller();
        $this->assertSame(Caller::DEFAULT_OPENSSL_EXECUTABLE, $caller->getExecutable());
    }

    public function testConstructWithExecutableName(): void
    {
        $caller = new Caller('my-openssl');
        $this->assertSame('my-openssl', $caller->getExecutable());
    }

    public function testCallerWithNullCharacterOnTemplate(): void
    {
        if (in_array(PHP_OS_FAMILY, ['Windows', 'Unknown'])) {
            $this->markTestSkipped('Expected exception on non-windows systems');
        }
        $caller = new Caller();
        $this->expectException(OpenSSLException::class);
        $caller->call('?', ["\0"]);
    }

    private function createFakeCaller(string $command, int $exitCode, string $output, string $errors): Caller
    {
        /** @var MockObject&Process $process */
        $process = $this->createMock(Process::class);
        $process->method('run')->willReturn($exitCode);
        $process->method('getCommandLine')->willReturn($command);
        $process->method('getOutput')->willReturn($output);
        $process->method('getErrorOutput')->willReturn($errors);

        return new class ($process) extends Caller {
            public function __construct(private Process $process)
            {
                parent::__construct('command');
            }

            // change method visibility
            public function createProcess(array $command, array $environment): Process
            {
                return $this->process;
            }
        };
    }

    public function testRunUsingMockedProcessExpectingError(): void
    {
        $caller = $this->createFakeCaller('command', 15, 'output', 'errors');

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

    public function testRunUsingMockedProcessExpectingSuccess(): void
    {
        $caller = $this->createFakeCaller('openssl', 0, 'OK', '');

        $callResponse = $caller->call('foo', []);
        $this->assertSame(0, $callResponse->exitStatus());
        $this->assertSame('openssl', $callResponse->commandLine());
        $this->assertSame('OK', $callResponse->output());
        $this->assertSame('', $callResponse->errors());
    }
}
