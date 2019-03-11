<?php
namespace CfdiUtils\Utils\Internal;

/**
 * Execute a command and retrieve results
 *
 * NOTE: Changes on this file will not be considering a BC since this utility class is for internal usage only
 *
 * @internal
 */
class ShellExec
{
    /** @var string */
    private $command;

    /** @var bool */
    private $captureErrorsFlag;

    public function __construct(
        string $command,
        bool $captureErrorsFlag
    ) {
        if ('' === $command) {
            throw new \InvalidArgumentException('Command was not set');
        }
        $this->command = $command;
        $this->captureErrorsFlag = $captureErrorsFlag;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getCaptureErrorsFlag(): bool
    {
        return $this->captureErrorsFlag;
    }

    public function operatingSystemIsWindows(): bool
    {
        return (0 === strpos(strtoupper(PHP_OS), 'WIN'));
    }

    public function nullByOs(): string
    {
        return $this->operatingSystemIsWindows() ? 'NUL' : '/dev/null';
    }

    public function exec(): ShellExecResult
    {
        if ($this->getCaptureErrorsFlag()) {
            return $this->execCapturingErrors();
        }

        return $this->execIgnoringErrors();
    }

    private function execCapturingErrors(): ShellExecResult
    {
        $errFile = TemporaryFile::create();
        try {
            $result = $this->execUsingErrorsFile($errFile->getPath());
            $errors = strval(file_get_contents($errFile->getPath()));
            return new ShellExecResult($result->exitStatus(), $result->output(), $errors);
        } finally {
            $errFile->remove();
        }
    }

    private function execIgnoringErrors(): ShellExecResult
    {
        return $this->execUsingErrorsFile($this->nullByOs());
    }

    private function execUsingErrorsFile(string $stdErrFile): ShellExecResult
    {
        $null = $this->nullByOs();
        $output = [];
        $exitCode = -1;
        $command = $this->getCommand();
        @exec($command . " 2> $stdErrFile < $null", $output, $exitCode);
        return new ShellExecResult($exitCode, implode(PHP_EOL, $output), '');
    }

    public static function run(
        string $command,
        bool $captureErrorsFlag = false
    ): ShellExecResult {
        $shellExec = new self($command, $captureErrorsFlag);
        return $shellExec->exec();
    }
}
