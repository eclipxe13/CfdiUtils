<?php
namespace CfdiUtils\OpenSSL;

class CallResponse
{
    /** @var string */
    private $command;

    /** @var string */
    private $output;

    /** @var string */
    private $errors;

    /** @var int */
    private $exitCode;

    public function __construct(string $command, string $output, string $errors, int $exitCode)
    {
        $this->command = $command;
        $this->output = $output;
        $this->errors = $errors;
        $this->exitCode = $exitCode;
    }

    public function command(): string
    {
        return $this->command;
    }

    public function output(): string
    {
        return $this->output;
    }

    public function errors(): string
    {
        return $this->errors;
    }

    public function exitCode(): int
    {
        return $this->exitCode;
    }
}
