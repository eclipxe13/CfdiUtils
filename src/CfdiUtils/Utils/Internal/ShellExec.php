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

    /** @var int */
    private $waitMinimalMs;

    /** @var int */
    private $waitMaximumMs;

    /** @var int */
    private $waitIncrementalFactor;

    public function __construct(
        string $command,
        array $environment,
        int $waitMinimalMs,
        int $waitMaximumMs,
        int $waitIncrementalFactor
    ) {
        if ('' === $command) {
            throw new \InvalidArgumentException('Command was not set');
        }
        if ($waitMinimalMs < 10) {
            throw new \InvalidArgumentException('The minimal wait in milliseconds cannot be lower than 10');
        }
        if ($waitMaximumMs < $waitMinimalMs) {
            throw new \InvalidArgumentException('The minimal wait in milliseconds cannot be higher than minimal');
        }
        if ($waitMaximumMs > 1000) {
            throw new \InvalidArgumentException('The minimal wait in milliseconds cannot be higher than 1000');
        }
        if ($waitIncrementalFactor < 2) {
            throw new \InvalidArgumentException('The wait incremental factor cannot be higher than 1000');
        }
        $this->command = $command;
        $this->environment = $environment;
        $this->waitMinimalMs = $waitMinimalMs;
        $this->waitMaximumMs = $waitMaximumMs;
        $this->waitIncrementalFactor = $waitIncrementalFactor;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getEnvironment(): array
    {
        return $this->environment;
    }

    public function getWaitMinimalMs(): int
    {
        return $this->waitMinimalMs;
    }

    public function getWaitMaximumMs(): int
    {
        return $this->waitMaximumMs;
    }

    public function getWaitIncrementalFactor(): int
    {
        return $this->waitIncrementalFactor;
    }

    public function exec()
    {
        $specs = [
            0 => ['pipe', 'r'], // stdin
            1 => ['pipe', 'w'], // stdout
            2 => ['pipe', 'w'], // stderr
        ];
        $pipes = [];
        $process = proc_open($this->getCommand(), $specs, $pipes, null, $this->getEnvironment());
        if (false === $process) {
            return new \RuntimeException(sprintf('Unable to execute: %s', $this->getCommand()));
        }
        fclose($pipes[0]);

        $stdout = new ShellExecPipeReader($pipes[1]);
        $stderr = new ShellExecPipeReader($pipes[2]);

        $waitMs = $this->getWaitMinimalMs();
        while ($stdout->continueReading() || $stderr->continueReading()) {
            $anyRead = false;
            if ($stdout->continueReading()) {
                $anyRead = $stdout->read();
            }
            if ($stderr->continueReading()) {
                $anyRead = $stderr->read() || $anyRead;
            }
            if ($anyRead) {
                $waitMs = $this->getWaitMinimalMs();
            } else {
                usleep($waitMs);
                if ($waitMs < $this->getWaitMaximumMs()) {
                    $waitMs = $waitMs * $this->getWaitIncrementalFactor();
                }
            }
        }
        $exitStatus = proc_close($process);

        return new ShellExecResult($exitStatus, $stdout->buffer(), $stderr->buffer());
    }

    public static function run(
        string $command,
        array $environment = [],
        int $waitMinimalMs = 10,
        int $waitMaximumMs = 360,
        int $waitIncrementalFactor = 2
    ): ShellExecResult {
        $shellExec = new self($command, $environment, $waitMinimalMs, $waitMaximumMs, $waitIncrementalFactor);
        return $shellExec->exec();
    }
}
