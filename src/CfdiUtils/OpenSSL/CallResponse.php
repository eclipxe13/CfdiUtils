<?php

namespace CfdiUtils\OpenSSL;

class CallResponse
{
    /** @var string */
    private $commandLine;

    /** @var string */
    private $output;

    /** @var string */
    private $errors;

    /** @var int */
    private $exitStatus;

    public function __construct(string $commandLine, string $output, string $errors, int $exitStatus)
    {
        $this->commandLine = $commandLine;
        $this->output = $output;
        $this->errors = $errors;
        $this->exitStatus = $exitStatus;
    }

    public function commandLine(): string
    {
        return $this->commandLine;
    }

    public function output(): string
    {
        return $this->output;
    }

    public function errors(): string
    {
        return $this->errors;
    }

    public function exitStatus(): int
    {
        return $this->exitStatus;
    }
}
