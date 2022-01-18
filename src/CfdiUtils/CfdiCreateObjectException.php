<?php

namespace CfdiUtils;

use LogicException;
use UnexpectedValueException;

final class CfdiCreateObjectException extends UnexpectedValueException
{
    /** @var array<string, UnexpectedValueException> */
    private $versionExceptions;

    /**
     * @param array<string, UnexpectedValueException> $versionException
     */
    private function __construct(string $message, array $versionException)
    {
        parent::__construct($message);
        $this->versionExceptions = $versionException;
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
