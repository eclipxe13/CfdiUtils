<?php

namespace CfdiUtils\OpenSSL;

class CallResponse
{
    public function __construct(
        private string $commandLine,
        private string $output,
        private string $errors,
        private int $exitStatus,
    ) {
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
