<?php

namespace CfdiUtils\Internals;

/**
 * Contains the result of ShellExec::exec()
 *
 * @see ShellExec
 *
 * NOTE: Changes will not be considering a bracking compatibility change since this utility is for internal usage only
 * @internal
 */
class ShellExecResult
{
    /** @var string */
    private $output;

    /** @var string */
    private $errors;

    /** @var string */
    private $commandLine;

    /** @var int */
    private $exitStatus;

    public function __construct(string $commandLine, int $exitStatus, string $output, string $errors)
    {
        $this->commandLine = $commandLine;
        $this->exitStatus = $exitStatus;
        $this->output = $output;
        $this->errors = $errors;
    }

    public function commandLine(): string
    {
        return $this->commandLine;
    }

    public function exitStatus(): int
    {
        return $this->exitStatus;
    }

    public function output(): string
    {
        return $this->output;
    }

    public function errors(): string
    {
        return $this->errors;
    }
}
