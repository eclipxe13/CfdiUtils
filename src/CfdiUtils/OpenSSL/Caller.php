<?php

namespace CfdiUtils\OpenSSL;

use CfdiUtils\Internals\ShellExec;
use CfdiUtils\Internals\ShellExecTemplate;

class Caller
{
    const DEFAULT_OPENSSL_EXECUTABLE = 'openssl';

    /** @var string */
    private $executable;

    public function __construct(string $executable = '')
    {
        $this->executable = $executable ?: static::DEFAULT_OPENSSL_EXECUTABLE;
    }

    public function getExecutable(): string
    {
        return $this->executable;
    }

    public function call(string $template, array $arguments, array $environment = []): CallResponse
    {
        try {
            // build command for shellExec
            array_unshift($arguments, $this->getExecutable());
            $command = ($this->createShellExecTemplate())->create('? ' . $template, $arguments);

            // create ShellExec
            $shellExec = $this->createShellExec($command, $environment);
        } catch (\Throwable $exception) {
            throw new OpenSSLException('Unable to build command', 0, $exception);
        }

        // execute ShellExec
        $execution = $shellExec->run();

        // build response
        $callResponse = new CallResponse(
            $execution->commandLine(),
            $execution->output(),
            $execution->errors(),
            $execution->exitStatus()
        );

        // eval response
        if (0 !== $callResponse->exitStatus()) {
            throw new OpenSSLCallerException($callResponse);
        }

        return $callResponse;
    }

    protected function createShellExec(array $command, array $environment): ShellExec
    {
        return new ShellExec($command, $environment);
    }

    protected function createShellExecTemplate(): ShellExecTemplate
    {
        return new ShellExecTemplate();
    }
}
