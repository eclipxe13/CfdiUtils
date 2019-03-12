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
        $this->executable = $executable;
    }

    /**
     * @return string Configured executable or DEFAULT_OPENSSL_EXECUTABLE
     */
    public function getExecutable(): string
    {
        return $this->executable ?: static::DEFAULT_OPENSSL_EXECUTABLE;
    }

    public function run(string $template, array $arguments, array $environment = [])
    {
        $command = $this->makeCommandArray($template, $arguments);
        $shellExec = $this->makeShellExec($command, $environment);
        $execution = $shellExec->exec();
        if ($execution->exitStatus() !== 0) {
            throw new OpenSSLCallerException($execution);
        }
    }

    public function makeShellExec(array $command, array $environment): ShellExec
    {
        return new ShellExec($command, $environment);
    }

    public function makeCommandArray($template, array $arguments): array
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
