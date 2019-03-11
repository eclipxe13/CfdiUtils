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

    /** @var array */
    private $environment;

    /** @var bool */
    private $captureErrorsFlag;

    public function __construct(
        string $command,
        array $environment,
        bool $captureErrorsFlag
    ) {
        if ('' === $command) {
            throw new \InvalidArgumentException('Command was not set');
        }
        foreach ($environment as $key => $value) {
            if (! boolval(preg_match('/^[a-zA-Z0-9_]+$/', $key))) {
                throw new \InvalidArgumentException('Environment variable name is not safe');
            }
        }
        $this->command = $command;
        $this->environment = $environment;
        $this->captureErrorsFlag = $captureErrorsFlag;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getEnvironment(): array
    {
        return $this->environment;
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

    private function getEnvironmentAsCommand(): string
    {
        return ($this->operatingSystemIsWindows()) ? $this->environmentWindows() : $this->environmentPosix();
    }

    private function environmentWindows(): string
    {
        return implode('&&', array_filter(array_map(
            function (string $key, string $value): string {
                return 'set ' . escapeshellarg("$key=$value");
            },
            array_keys($this->environment),
            $this->environment
        ))) . '&&';
    }

    private function environmentPosix(): string
    {
        return implode(' ', array_filter(array_map(
            function (string $key, string $value): string {
                return $key . '=' . escapeshellarg($value);
            },
            array_keys($this->environment),
            $this->environment
        ))) . ' ';
    }

    private function execUsingErrorsFile(string $stdErrFile): ShellExecResult
    {
        $null = $this->nullByOs();
        $output = [];
        $exitCode = -1;
        $command = $this->getEnvironmentAsCommand() . $this->getCommand() . " 2> $stdErrFile < $null";
        @exec($command, $output, $exitCode);
        return new ShellExecResult($exitCode, implode(PHP_EOL, $output), '');
    }

    public static function run(
        string $command,
        array $environment = [],
        bool $captureErrorsFlag = false
    ): ShellExecResult {
        $shellExec = new self($command, $environment, $captureErrorsFlag);
        return $shellExec->exec();
    }
}
