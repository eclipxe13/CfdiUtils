<?php
namespace CfdiUtils\Utils\Internal;

/**
 * Build a command array from a template
 *
 * NOTE: Changes on this file will not be considering a BC since this utility class is for internal usage only
 *
 * @internal
 */
class ShellExecTemplate
{
    public function create(string $template, array $arguments): array
    {
        $command = [];
        $parts = array_filter(explode(' ', $template) ?: [], function (string $part): bool {
            return ('' !== $part);
        });

        $argumentPosition = 0;
        foreach ($parts as $value) {
            if ('?' === $value) { // argument insert
                $value = $arguments[$argumentPosition] ?? '';
                $argumentPosition = $argumentPosition + 1;
            }
            $command[] = $value;
        }

        return $command;
    }
}
