<?php

namespace CfdiUtils;

use LogicException;
use UnexpectedValueException;

final class CfdiCreateObjectException extends UnexpectedValueException
{
    /**
     * @param array<string, UnexpectedValueException> $versionExceptions
     */
    private function __construct(string $message, private array $versionExceptions)
    {
        parent::__construct($message);
    }

    /**
     * @param array<string, UnexpectedValueException> $versionException
     */
    public static function withVersionExceptions(array $versionException): self
    {
        return new self('Unable to read DOMDocument as CFDI', $versionException);
    }

    /** @return string[] */
    public function getVersions(): array
    {
        return array_keys($this->versionExceptions);
    }

    public function getExceptionByVersion(string $version): UnexpectedValueException
    {
        if (! isset($this->versionExceptions[$version])) {
            throw new LogicException("Version $version does not have any exception");
        }
        return $this->versionExceptions[$version];
    }

    /** @return array<string, UnexpectedValueException> */
    public function getExceptions(): array
    {
        return $this->versionExceptions;
    }
}
