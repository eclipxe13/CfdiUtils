<?php

require __DIR__ . '/bootstrap.php';

exit(call_user_func(function (string $command, string ...$arguments): int {
    $files = [];
    $askForHelp = false;
    $noCache = false;
    $clean = false;
    foreach ($arguments as $argument) {
        if (in_array($argument, ['-h', '--help'], true)) {
            $askForHelp = true;
            break; // no need to continue with other arguments
        }
        if (in_array($argument, ['-c', '--clean'], true)) {
            $clean = true;
            continue;
        }
        if ($argument === '--no-cache') {
            $noCache = true;
            continue;
        }
        $files[] = $argument;
    }
    $files = array_filter($files);

    if ($askForHelp || ! count($files)) {
        echo implode(PHP_EOL, [
            basename($command) . ' [-h|--help] [-c|--clean] [--no-cache] cfdi.xml...',
            '  -h, --help   Show this help',
            '  -c, --clean  Clean CFDI before validation',
            '  --no-cache   Tell resolver to not use local cache',
            "  cfdi.xml     Files to check, as many as needed, don't allow wilcards",
            '  WARNING: This program can change at any time! Do not depend on this file or its results!',
        ]), PHP_EOL;
        return 0;
    }

    $validator = new \CfdiUtils\CfdiValidator33();
    if ($noCache) {
        $validator->getXmlResolver()->setLocalPath('');
    }
    foreach ($files as $file) {
        $xmlContent = strval(file_get_contents($file));
        if ($clean) {
            $xmlContent = \CfdiUtils\Cleaner\Cleaner::staticClean($xmlContent);
        }
        $asserts = $validator->validateXml($xmlContent);
        print_r(array_filter([
            'file' => $file,
            'asserts' => $asserts->count(),
            'hasErrors' => $asserts->hasErrors() ? 'yes' : 'no',
            'errors' => ($asserts->hasErrors()) ? $asserts->errors() : null,
            'hasWarnings' => $asserts->hasWarnings() ? 'yes' : 'no',
            'warnings' => ($asserts->hasWarnings()) ? $asserts->warnings() : null,
        ]));
    }

    return 0;
}, ...$argv));
