<?php
namespace CfdiUtils\Utils\Internal;

use Symfony\Component\Process\Process;

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

    public function __construct(
        string $command,
        array $environment
    ) {
        if ('' === $command) {
            throw new \InvalidArgumentException('Command was not set');
        }
        $this->command = $command;
        $this->environment = $environment;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getEnvironment(): array
    {
        return $this->environment;
    }

    public function exec(): ShellExecResult
    {
        $process = Process::fromShellCommandline($this->getCommand(), null, $this->getEnvironment());
        $process->run();
        return new ShellExecResult($process->getExitCode(), $process->getOutput(), $process->getErrorOutput());
    }

    public static function run(
        string $command,
        array $environment = []
    ): ShellExecResult {
        $shellExec = new self($command, $environment);
        return $shellExec->exec();
    }
}
