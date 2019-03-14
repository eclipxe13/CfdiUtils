<?php
namespace CfdiUtils\Utils\Internal;

/**
 * Internal class, contains the result of ShellExec::exec()
 *
 * NOTE: Changes on this file will not be considering a BC since this utility class is for internal usage only
 *
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
