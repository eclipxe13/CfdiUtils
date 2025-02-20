<?php

declare(strict_types=1);

namespace CfdiUtils\Development\ElementsMaker;

use JsonException;
use RuntimeException;
use stdClass;

final class SpecificationsReader
{
    private stdClass $data;

    public function __construct(stdClass $data)
    {
        $this->data = $data;
    }

    public static function fromFile(string $specFile): self
    {
        if (! file_exists($specFile)) {
            throw new RuntimeException("Specification file '$specFile' does not exists");
        }
        $specContents = file_get_contents($specFile);
        if (false === $specContents) {
            throw new RuntimeException("Unable to read $specFile");
        }
        return self::fromJsonString($specContents);
    }

    public static function fromJsonString(string $specContents): self
    {
        try {
            $data = json_decode($specContents, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('Unable to parse the JSON specification', 0, $exception);
        }
        if (! $data instanceof stdClass) {
            throw new RuntimeException('The JSON specification does not contains a valid root object');
        }
        return new self($data);
    }

    public function keyAsString(string $name): string
    {
        if (! isset($this->data->{$name})) {
            return '';
        }
        if (! is_string($this->data->{$name})) {
            return '';
        }
        return $this->data->{$name};
    }

    public function keyAsStdClass(string $name): stdClass
    {
        if (! isset($this->data->{$name})) {
            return (object) [];
        }
        if (! $this->data->{$name} instanceof stdClass) {
            return (object) [];
        }
        return $this->data->{$name};
    }
}
