<?php

namespace CfdiUtils\Internals;

/**
 * NormalizeLineEndingsTrait contains a private function normalizeLineEndings
 * This help the implementer class to work with EOL
 *
 * NOTE: Changes will not be considering a bracking compatibility change since this utility is for internal usage only
 * @internal
 */
trait NormalizeLineEndingsTrait
{
    /**
     * Changes EOL CRLF or LF to PHP_EOL.
     * This won't alter CR that are not at EOL.
     * This won't alter LFCR used in old Mac style
     *
     * @param string $content
     * @return string
     * @internal
     */
    private function normalizeLineEndings(string $content): string
    {
        // move '\r\n' or '\n' to PHP_EOL
        // first substitution '\r\n' -> '\n'
        // second substitution '\n' -> PHP_EOL
        // remove any EOL at the EOF
        return rtrim(str_replace(["\r\n", "\n"], ["\n", PHP_EOL], $content), PHP_EOL);
    }
}
