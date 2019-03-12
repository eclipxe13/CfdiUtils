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
    /** @var array */
    private $command;

    /** @var array */
    private $environment;

    public function __construct(array $command, array $environment = [])
    {
        if ([] === $command) {
            throw new \InvalidArgumentException('Command was not set');
        }
        $this->command = $command;
        $this->environment = $environment;
    }

    public function getCommand(): array
    {
        return $this->command;
    }

    public function getEnvironment(): array
    {
        return $this->environment;
    }

    public function exec(): ShellExecResult
    {
        $process = new Process($this->getCommand());
        $process->setEnv($this->getEnvironment());
        $process->run();
        return new ShellExecResult(
            $process->getCommandLine(),
            $process->getExitCode() ?? -1,
            $process->getOutput(),
            $process->getErrorOutput()
        );
    }
}
