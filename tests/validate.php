<?php

require __DIR__ . '/bootstrap.php';

exit(call_user_func(function (string $command, string ...$arguments): int {
    $files = [];
    $askForHelp = false;
    foreach ($arguments as $argument) {
        if (in_array($argument, ['-h', '--help'])) {
            $askForHelp = true;
            break; // no need to continue with other arguments
        }
        $files[] = $argument;
    }
    $files = array_filter($files);

    if ($askForHelp || ! count($files)) {
        echo basename($command) . ' [-h|--help] file_1.xml, file_2.xml, file_n.xml', PHP_EOL;
        echo '  WARNING: This can change at any time! Do not depend on this file or its results!', PHP_EOL;
        return 0;
    }

    $validator = new \CfdiUtils\CfdiValidator33();
    foreach ($files as $file) {
        $asserts = $validator->validateXml(file_get_contents($file));
        print_r(array_filter([
            'file' => $file,
            'hasErrors' => $asserts->hasErrors() ? 'yes' : 'no',
            'errors' => $asserts->hasErrors() ? $asserts->errors() : null,
            'hasWarnings' => $asserts->hasWarnings() ? 'yes' : 'no',
            'warnings' => ($asserts->hasWarnings()) ? $asserts->warnings() : null,
        ]));
    }

    return 0;
}, ...$argv));
