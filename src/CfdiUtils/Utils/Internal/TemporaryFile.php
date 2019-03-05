<?php
namespace CfdiUtils\Utils\Internal;

/**
 * Utility to work with temporary the creation of files.
 * Use it instead of \tempnam PHP function
 *
 * NOTE: Changes on this file will not be considering a BC since this utility class is for internal usage only
 *
 * @internal
 */
final class TemporaryFile
{
    /** @var string */
    private $filename;

    private function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public static function create(string $prefix = '', string $directory = ''): self
    {
        if ('' === $directory) {
            $directory = sys_get_temp_dir();
        }
        $previousErrorLevel = error_reporting(0);
        $filename = strval(tempnam($directory, $prefix));
        error_reporting($previousErrorLevel);
        if (dirname($filename) !== $directory) {
            unlink($filename);
            $filename = '';
        }
        if ('' === $filename) {
            throw new \RuntimeException(
                sprintf('Unable to create a temporary file on %s using prefix %s', $directory, $prefix ?: '(none)')
            );
        }
        return new static($filename);
    }

    public function getPath(): string
    {
        return $this->filename;
    }

    public function remove()
    {
        $filename = $this->getPath();
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
}
