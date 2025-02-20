<?php

declare(strict_types=1);

namespace CfdiUtils\Development;

abstract class BaseCliApplication
{
    private string $command;

    /** @var string[] */
    private array $arguments;

    abstract public function printHelp(): void;

    abstract public function execute(): int;

    final public function __construct(string $command, string ...$arguments)
    {
        $this->command = $command;
        $this->arguments = $arguments;
    }

    public function __invoke(): int
    {
        if ([] !== array_intersect(['-h', '--help'], $this->arguments)) {
            $this->printHelp();
            return 0;
        }

        try {
            return $this->execute();
        } catch (\Throwable $exception) {
            file_put_contents('php://stderr', $exception->getMessage() . PHP_EOL, FILE_APPEND);
            // file_put_contents('php://stderr', get_class($exception) . ': ' . $exception->getMessage() . PHP_EOL, FILE_APPEND);
            // file_put_contents('php://stderr', $exception->getTraceAsString() . PHP_EOL, FILE_APPEND);
            return min(1, $exception->getCode());
        }
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    /** @return string[] */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getArgument(int $index): string
    {
        return $this->arguments[$index] ?? '';
    }
}
