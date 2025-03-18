<?php

declare(strict_types=1);

namespace CfdiUtils\Development\ElementsMaker;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use stdClass;
use Traversable;

final class Structure implements Countable, IteratorAggregate
{
    /** @var Structure[] */
    private array $children;

    public function __construct(private string $name, private bool $multiple, self ...$children)
    {
        $this->children = $children;
    }

    public static function makeFromStdClass(string $name, stdClass $data): self
    {
        $multiple = false;
        if (isset($data->{'multiple'}) && is_bool($data->{'multiple'})) {
            $multiple = $data->{'multiple'};
        }
        $children = [];

        foreach (get_object_vars($data) as $key => $value) {
            if ($value instanceof stdClass) {
                $children[] = self::makeFromStdClass($key, $value);
            }
        }

        return new self($name, $multiple, ...$children);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    /** @return Traversable<int, Structure> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->children);
    }

    /** @return string[] */
    public function getChildrenNames(string $prefix): array
    {
        return array_unique(
            array_map(
                fn (self $structure): string => $prefix . $structure->getName(),
                $this->children
            )
        );
    }

    public function count(): int
    {
        return count($this->children);
    }
}
