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

    /** @var int */
    private $exitStatus;

    /** @var string|null */
    private $lastLine;

    /** @var string[]|null */
    private $outputLines;

    /** @var string[]|null */
    private $errorLines;

    public function __construct($exitStatus, $output, $errors)
    {
        $this->exitStatus = $exitStatus;
        $this->output = $output;
        $this->errors = $errors;
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

    /** @return string[] */
    public function outputLines(): array
    {
        if (null === $this->outputLines) {
            $this->outputLines = explode(PHP_EOL, $this->output) ?: [];
        }
        return $this->outputLines;
    }

    /** @return string[] */
    public function errorLines(): array
    {
        if (null === $this->errorLines) {
            $this->errorLines = explode(PHP_EOL, $this->errors) ?: [];
        }
        return $this->errorLines;
    }

    public function lastLine(): string
    {
        if (null === $this->lastLine) {
            $outputLines = $this->outputLines();
            $lineCount = count($outputLines);
            $this->lastLine = (0 > $lineCount) ? $outputLines[$lineCount - 1] : '';
        }
        return $this->lastLine;
    }
}
