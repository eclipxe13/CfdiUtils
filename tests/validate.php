<?php

use CfdiUtils\Cfdi;
use CfdiUtils\CfdiValidator33;
use CfdiUtils\CfdiValidator40;
use CfdiUtils\Cleaner\Cleaner;
use CfdiUtils\Validate\Assert;
use CfdiUtils\Validate\Asserts;

require __DIR__ . '/bootstrap.php';

exit(call_user_func(new class(...$argv) {
    /** @var string */
    private $command;

    /** @var string[] */
    private $arguments;

    /** @var array<CfdiValidator33|CfdiValidator40> */
    private $validators;

    private const SUCCESS = 0;

    private const ERROR = 1;

    private const FAILURE = 2;

    public function __construct(string $command, string ...$arguments)
    {
        $this->command = $command;
        $this->arguments = $arguments;
        $this->validators = [
            '3.3' => new CfdiValidator33(),
            '4.0' => new CfdiValidator40(),
        ];
    }

    public function __invoke(): int
    {
        if ([] !== array_intersect(['-h', '--help'], $this->arguments)) {
            $this->printHelp();
            return self::SUCCESS;
        }

        $files = [];
        $noCache = false;
        $clean = false;
        foreach ($this->arguments as $argument) {
            if (in_array($argument, ['-c', '--clean'], true)) {
                $clean = true;
                continue;
            }
            if ('--no-cache' === $argument) {
                $noCache = true;
                continue;
            }
            $files[] = $argument;
        }
        $files = array_unique(array_filter($files));
        if ([] === $files) {
            printf("FAIL: No files were specified\n");
            return 2;
        }

        if ($noCache) {
            foreach ($this->validators as $validator) {
                $validator->getXmlResolver()->setLocalPath('');
            }
        }

        set_error_handler(function (int $number, string $message) {
            throw new Error($message, $number);
        });

        $exitCode = self::SUCCESS;
        foreach ($files as $file) {
            printf("File: %s\n", $file);
            try {
                $asserts = $this->validateFile($file, $clean);
            } catch (Throwable $exception) {
                printf("FAIL: (%s) %s\n\n", get_class($exception), $exception->getMessage());
                $exitCode = self::FAILURE;
                continue;
            }
            if (! $this->printAsserts($asserts)) {
                $exitCode = self::ERROR;
            }
            printf("\n");
        }

        return $exitCode;
    }

    private function printHelp(): void
    {
        $command = basename($this->command);
        echo <<< EOH
        $command Validates CFDI files
        Syntax:
            $command [-h|--help] [-c|--clean] [--no-cache] cfdi.xml ...
        Arguments:
            -h, --help   Show this help
            -c, --clean  Clean CFDI before validation
            --no-cache   Tell resolver to not use local cache
            cfdi.xml     Files to check, as many as needed
        Exit codes:
            0 - All files were validated with success
            1 - At least one file contains errors or warnings
            2 - At least one file produce an exception

        WARNING: This program can change at any time! Do not depend on this file or its results!


        EOH;
    }

    private function printAsserts(Asserts $asserts): bool
    {
        $warnings = $asserts->warnings();
        $errors = $asserts->errors();
        printf(
            "Asserts: %s total, %s executed, %s ignored, %s success, %s warnings, %s errors.\n",
            $asserts->count(),
            $asserts->count() - count($asserts->nones()),
            count($asserts->nones()),
            count($asserts->oks()),
            count($warnings),
            count($errors)
        );
        foreach ($warnings as $warning) {
            $this->printAssert('WARNING', $warning);
        }
        foreach ($errors as $error) {
            $this->printAssert('ERROR', $error);
        }
        return [] === $errors && [] === $warnings;
    }

    private function printAssert(string $type, Assert $assert): void
    {
        $explanation = '';
        if ($assert->getExplanation()) {
            $explanation = sprintf("\n%s%s", str_repeat(' ', mb_strlen($type) + 2), $assert->getExplanation());
        }
        printf("%s: %s - %s%s\n", $type, $assert->getCode(), $assert->getTitle(), $explanation);
    }

    private function validateFile(string $file, bool $clean): Asserts
    {
        $xmlContent = (string) file_get_contents($file);
        if ($clean) {
            $xmlContent = Cleaner::staticClean($xmlContent);
        }
        return $this->validateXmlContent($xmlContent);
    }

    private function validateXmlContent(string $xmlContent): Asserts
    {
        $cfdi = Cfdi::newFromString($xmlContent);
        return $this->validateCfdi($cfdi);
    }

    private function validateCfdi(Cfdi $cfdi): Asserts
    {
        $validator = $this->getValidatorForVersion($cfdi->getVersion());
        return $validator->validate($cfdi->getSource(), $cfdi->getNode());
    }

    /** @return CfdiValidator33|CfdiValidator40 */
    private function getValidatorForVersion(string $version)
    {
        if (! isset($this->validators[$version])) {
            throw new Exception(sprintf('There is no validator for "%s"', $version));
        }
        return $this->validators[$version];
    }
}));
