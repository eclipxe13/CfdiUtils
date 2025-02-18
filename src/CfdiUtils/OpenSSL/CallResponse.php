<?php

namespace CfdiUtils\OpenSSL;

class CallResponse
{
    private string $commandLine;

    private string $output;

    private string $errors;

    private int $exitStatus;

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
