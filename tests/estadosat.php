<?php

use \CfdiUtils\ConsultaCfdiSat\Config;
use \CfdiUtils\ConsultaCfdiSat\WebService;

require __DIR__ . '/bootstrap.php';

exit(call_user_func(function (string $command, string ...$arguments): int {
    $files = [];
    $askForHelp = false;
    $wsdlLocation = '';
    foreach ($arguments as $argument) {
        if (in_array($argument, ['-h', '--help'], true)) {
            $askForHelp = true;
            break; // no need to continue with other arguments
        }
        if (in_array($argument, ['--local-wsdl'], true)) {
            $wsdlLocation = Config::getLocalWsdlLocation();
            continue; // no need to continue with other arguments
        }
        $files[] = $argument;
    }
    $files = array_filter($files);

    if ($askForHelp || ! count($files)) {
        echo implode(PHP_EOL, [
            basename($command) . ' [-h|--help] cfdi.xml...',
            '  -h, --help     Show this help',
            '  --local-wsdl   Use local wsdl file instead of service url',
            "  cfdi.xml       Files to check, as many as needed, don't allow wilcards",
            '  WARNING: This program can change at any time! Do not depend on this file or its results!',
        ]), PHP_EOL;
        return 0;
    }

    $config = new Config(10, true, '', $wsdlLocation);
    $webService = new WebService($config);

    foreach ($files as $file) {
        $cfdi = \CfdiUtils\Cfdi::newFromString((string) file_get_contents($file));
        $request = \CfdiUtils\ConsultaCfdiSat\RequestParameters::createFromCfdi($cfdi);
        $response = $webService->request($request);

        print_r(array_filter([
            'file' => $file,
            'Status CFDI' => $response->getCfdi(),
            'Code' => $response->getCode(),
        ]));
    }

    return 0;
}, ...$argv));
