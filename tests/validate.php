<?php

require __DIR__ . '/bootstrap.php';

exit(call_user_func(function (string $command, string ...$arguments): int {
    $files = [];
    $askForHelp = false;
    $noCache = false;
    foreach ($arguments as $argument) {
        if (in_array($argument, ['-h', '--help'])) {
            $askForHelp = true;
            break; // no need to continue with other arguments
        }
        if ($argument === '--no-cache') {
            $noCache = true;
            break;
        }
        $files[] = $argument;
    }
    $files = array_filter($files);

    if ($askForHelp || ! count($files)) {
        echo implode(PHP_EOL, [
            basename($command) . ' [-h|--help] [--no-cache] cfdi.xml...',
            '  -h, --help   Show this help',
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
        $asserts = $validator->validateXml(file_get_contents($file));
        print_r(array_filter([
            'file' => $file,
            'asserts' => $asserts->count(),
            'hasErrors' => $asserts->hasErrors() ? 'yes' : 'no',
            'errors' => $asserts->hasErrors() ? $asserts->errors() : null,
            'hasWarnings' => $asserts->hasWarnings() ? 'yes' : 'no',
            'warnings' => ($asserts->hasWarnings()) ? $asserts->warnings() : null,
        ]));
    }

    return 0;
}, ...$argv));
