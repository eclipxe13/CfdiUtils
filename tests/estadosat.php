<?php

use CfdiUtils\Cfdi;
use CfdiUtils\ConsultaCfdiSat\RequestParameters;
use \CfdiUtils\ConsultaCfdiSat\WebService;

require __DIR__ . '/bootstrap.php';

exit(call_user_func(function (string $command, string ...$arguments): int {
    $askForHelp = ([] !== array_intersect(['-h', '--help'], $arguments));
    $files = array_filter($arguments);

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
        if (! file_exists($file)) {
            echo "El archivo $file no existe", PHP_EOL;
            continue;
        }
        $cfdi = Cfdi::newFromString((string) file_get_contents($file));
        $request = RequestParameters::createFromCfdi($cfdi);
        $response = $webService->request($request);

        echo implode(PHP_EOL, [
            "    Archivo: $file",
            "  Expresión: {$request->expression()}",
            "   Petición: {$response->getCode()}",
            "Estado CFDI: {$response->getCfdi()}",
            " Cancelable: {$response->getCancellable()}",
            "Cancelación: {$response->getCancellationStatus()}",
            "       EFOS: {$response->getValidationEfos()}",
        ]), PHP_EOL;
    }

    return 0;
}, ...$argv));
