<?php
namespace CfdiUtils\OpenSSL;

trait NormalizeLineEndingsTrait
{
    protected function normalizeLineEndings(string $content): string
    {
        // move '\r\n' or '\n' to PHP_EOL
        // first substitution '\r\n' -> '\n'
        // second substitution '\n' -> PHP_EOL
        // remove any EOL at the EOF
        return rtrim(str_replace(["\r\n", "\n"], ["\n", PHP_EOL], $content), PHP_EOL);
    }
}
