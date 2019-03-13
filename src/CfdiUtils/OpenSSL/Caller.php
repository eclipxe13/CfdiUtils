<?php
namespace CfdiUtils\OpenSSL;

use CfdiUtils\Utils\Internal\ShellExec;

class Caller
{
    const DEFAULT_OPENSSL_EXECUTABLE = 'openssl';

    /** @var string */
    private $executable;

    public function __construct(string $executable = '')
    {
        $this->executable = $executable ?: static::DEFAULT_OPENSSL_EXECUTABLE;
    }

    /**
     * @return string Configured executable or DEFAULT_OPENSSL_EXECUTABLE
     */
    public function getExecutable(): string
    {
        return $this->executable;
    }

    public function call(string $template, array $arguments, array $environment = []): CallResponse
    {
        $command = $this->templateCommandToArrayArguments($template, $arguments);
        $shellExec = $this->createShellExec($command, $environment);
        $execution = $shellExec->run();
        $callResponse = new CallResponse(
            $execution->commandLine(),
            $execution->output(),
            $execution->errors(),
            $execution->exitStatus()
        );
        if ($execution->exitStatus() !== 0) {
            throw new OpenSSLCallerException($callResponse);
        }
        return $callResponse;
    }

    protected function createShellExec(array $command, array $environment): ShellExec
    {
        return new ShellExec($command, $environment);
    }

    protected function templateCommandToArrayArguments(string $template, array $arguments): array
    {
        $parts = explode(' ', trim($template)) ?: [];
        $command = [$this->getExecutable()];

        $argumentPosition = 0;
        foreach ($parts as $index => $value) {
            if ('' === $value) { // filter empty strings
                continue;
            }
            if ('?' === $value) { // argument insert
                $value = $arguments[$argumentPosition] ?? '';
                $argumentPosition = $argumentPosition + 1;
            }
            $command[] = $value;
        }

        return $command;
    }
}
