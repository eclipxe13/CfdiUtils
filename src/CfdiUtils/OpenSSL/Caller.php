<?php

namespace CfdiUtils\OpenSSL;

use CfdiUtils\Internals\CommandTemplate;
use Symfony\Component\Process\Process;
use Throwable;

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
            // build command for process run
            array_unshift($arguments, $this->getExecutable());
            $command = $this->createCommandTemplate()->create('? ' . $template, $arguments);

            // create Process
            $process = $this->createProcess($command, $environment);
        } catch (Throwable $exception) {
            throw new OpenSSLException('Unable to build command', 0, $exception);
        }

        // execute process
        $execution = $process->run();

        // build response
        $callResponse = new CallResponse(
            $process->getCommandLine(),
            $process->getOutput(),
            $process->getErrorOutput(),
            $execution
        );

        // eval response
        if (0 !== $execution) {
            throw new OpenSSLCallerException($callResponse);
        }

        return $callResponse;
    }

    protected function createProcess(array $command, array $environment): Process
    {
        return new Process($command, null, $environment);
    }

    protected function createCommandTemplate(): CommandTemplate
    {
        return new CommandTemplate();
    }
}
