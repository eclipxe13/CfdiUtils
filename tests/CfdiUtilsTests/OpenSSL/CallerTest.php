<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\Caller;
use CfdiUtils\OpenSSL\OpenSSLCallerException;
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

    public function providerTemplateCommandToArrayArguments()
    {
        return [
            'no argument' => ['foo bar baz', [], ['>', 'foo', 'bar', 'baz']],
            'replace with 2 replacements' => ['foo ? => ?', ['bar', 'baz'], ['>', 'foo', 'bar', '=>', 'baz']],
            'replace spaces' => ['foo   ?   => ? x', ['bar', 'baz'], ['>', 'foo', 'bar', '=>', 'baz', 'x']],
            'more ? than arguments' => ['foo   ?   => ? x', [], ['>', 'foo', '', '=>', '', 'x']],
            'less ? than arguments' => ['foo ? => ?', ['bar'], ['>', 'foo', 'bar', '=>', '']],
        ];
    }

    /**
     * @param string $template
     * @param array $arguments
     * @param array $expected
     * @dataProvider providerTemplateCommandToArrayArguments
     */
    public function testTemplateCommandToArrayArguments(string $template, array $arguments, array $expected)
    {
        $caller = new class('>') extends Caller {
            public function templateCommandToArrayArguments(string $template, array $arguments): array
            {
                $return = parent::templateCommandToArrayArguments($template, $arguments);
                return $return;
            }
        };

        $command = $caller->templateCommandToArrayArguments($template, $arguments);
        $this->assertSame($expected, $command);
    }

    public function testRunUsingMockedShellExecExpectingError()
    {
        $caller = new class() extends Caller {
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
