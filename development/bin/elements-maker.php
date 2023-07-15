<?php

use CfdiUtils\Development\BaseCliApplication;
use CfdiUtils\Development\ElementsMaker\ElementsMaker;

require __DIR__ . '/../../vendor/autoload.php';

exit(call_user_func(new class (...$argv) extends BaseCliApplication {
    public function printHelp(): void
    {
        $command = basename($this->getCommand());
        echo implode(PHP_EOL, [
            "$command - Creación de elementos a partir de una especificación.",
            'Sintaxis: ',
            "$command specification-file output-directory",
            '  specification-file: the location of the specification file',
            '  output-directory: the location where files should be written',
            '',
        ]);
    }

    public function execute(): int
    {
        $specFile = $this->getArgument(0);
        if ('' === $specFile) {
            throw new RuntimeException('Argument specification-file not set');
        }

        $outputDir = $this->getArgument(1);
        if ('' === $outputDir) {
            throw new RuntimeException('Argument output-directory not set');
        }

        $elementsMaker = ElementsMaker::make($specFile, $outputDir);
        $elementsMaker->write();
        return 0;
    }
}));
