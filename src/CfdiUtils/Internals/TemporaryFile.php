<?php

namespace CfdiUtils\Internals;

/**
 * Utility to work with the creation of temporary files.
 *
 * NOTE: Changes will not be considering a bracking compatibility change since this utility is for internal usage only
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

    public static function create(string $directory = ''): self
    {
        if ('' === $directory) {
            $directory = sys_get_temp_dir();
            if (in_array($directory, ['', '.'], true)) {
                throw new \RuntimeException('System has an invalid default temp dir');
            }
        }

        $previousErrorLevel = error_reporting(0);
        $filename = strval(tempnam($directory, ''));
        error_reporting($previousErrorLevel);

        // must check realpath since windows can return different paths for same location
        if (realpath(dirname($filename)) !== realpath($directory)) {
            unlink($filename);
            $filename = '';
        }

        if ('' === $filename) {
            throw new \RuntimeException(sprintf('Unable to create a temporary file'));
        }

        return new static($filename);
    }

    public function getPath(): string
    {
        return $this->filename;
    }

    public function retriveContents(): string
    {
        return strval(file_get_contents($this->filename));
    }

    public function storeContents(string $contents)
    {
        file_put_contents($this->filename, $contents);
    }

    public function remove()
    {
        $filename = $this->getPath();
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    public function __toString(): string
    {
        return $this->getPath();
    }

    public function runAndRemove(\Closure $fn)
    {
        try {
            return $fn();
        } finally {
            $this->remove();
        }
    }
}
