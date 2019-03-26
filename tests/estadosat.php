<?php

use \CfdiUtils\ConsultaCfdiSat\Config;
use \CfdiUtils\ConsultaCfdiSat\WebService;

require __DIR__ . '/bootstrap.php';

exit(call_user_func(function (string $command, string ...$arguments): int {
    $files = [];
    $askForHelp = false;
    foreach ($arguments as $argument) {
        if (in_array($argument, ['-h', '--help'], true)) {
            $askForHelp = true;
            break; // no need to continue with other arguments
        }
        $files[] = $argument;
    }
    $files = array_filter($files);

    if ($askForHelp || ! count($files)) {
        echo implode(PHP_EOL, [
            basename($command) . ' [-h|--help] cfdi.xml...',
            '  -h, --help     Show this help',
            "  cfdi.xml       Files to check, as many as needed, don't allow wilcards",
            '  WARNING: This program can change at any time! Do not depend on this file or its results!',
        ]), PHP_EOL;
        return 0;
    }

    $webService = new WebService();

    foreach ($files as $file) {
        $cfdi = \CfdiUtils\Cfdi::newFromString((string) file_get_contents($file));
        $request = \CfdiUtils\ConsultaCfdiSat\RequestParameters::createFromCfdi($cfdi);
        $response = $webService->request($request);

        print_r(array_filter([
            'file' => $file,
            'Petición' => $response->getCfdi(),
            'Estado CFDI' => $response->getCode(),
            'Cancelable' => $response->getCancellable(),
            'Estado cancelación' => $response->getCancellationStatus(),
        ]));
    }

    return 0;
}, ...$argv));
