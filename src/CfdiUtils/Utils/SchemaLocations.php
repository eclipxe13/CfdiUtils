<?php

namespace CfdiUtils\Utils;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class SchemaLocations implements Countable, IteratorAggregate
{
    /** @var array<string, string> */
    private array $pairs = [];

    /**
     * SchemaLocations constructor.
     * @param array<string, string> $pairs
     */
    public function __construct(array $pairs = [])
    {
        foreach ($pairs as $namespace => $location) {
            $this->append($namespace, $location);
        }
    }

    /**
     * Create a collection of namespaces (key) and location (value)
     */
    public static function fromString(string $schemaLocationValue, bool $includeLastUnpairedItem): self
    {
        $schemaLocations = new self();
        $components = array_values(array_filter(explode(' ', $schemaLocationValue)));
        $length = count($components);
        for ($c = 0; $c < $length; $c = $c + 2) {
            $location = $components[$c + 1] ?? '';
            if ('' !== $location || $includeLastUnpairedItem) {
                $schemaLocations->append($components[$c], $location);
            }
        }
        return $schemaLocations;
    }

    /**
     * Create a collection of namespaces (key) and location (value)
     * All locations *must* end with '.xsd', If not they are considered namespaces
     */
    public static function fromStingStrictXsd(string $schemaLocationValue): self
    {
        $schemaLocations = new self();
        $components = array_values(array_filter(explode(' ', $schemaLocationValue)));
        $length = count($components);
        for ($c = 0; $c < $length; $c = $c + 1) {
            $namespace = $components[$c];
            $location = $components[$c + 1] ?? '';
            if ('.xsd' === (substr($location, -4) ?: '')) {
                $schemaLocations->append($namespace, $location);
                $c = $c + 1; // skip ns declaration
                continue;
            }
            $schemaLocations->append($namespace, '');
        }
        return $schemaLocations;
    }

    public function isEmpty(): bool
    {
        return [] === $this->pairs;
    }

    /**
     * Return an array of pairs using namespace as key and location as value
     *
     * @return array<string, string>
     */
    public function pairs(): array
    {
        return $this->pairs;
    }

    public function has(string $namespace): bool
    {
        return array_key_exists($namespace, $this->pairs);
    }

    /**
     * Get an array with namespaces that has empty location
     *
     * @return string[]
     */
    public function getNamespacesWithoutLocation(): array
    {
        return array_keys(array_filter($this->pairs, fn (string $location): bool => '' === $location));
    }

    public function hasAnyNamespaceWithoutLocation(): bool
    {
        return [] !== $this->getNamespacesWithoutLocation();
    }

    public function append(string $namespace, string $location): void
    {
        $this->pairs[$namespace] = $location;
    }

    public function remove(string $namespace): void
    {
        unset($this->pairs[$namespace]);
    }

    /**
     * Return the collection of namespace location separated by spaces
     */
    public function asString(): string
    {
        return implode(' ', array_filter(array_map(
            function (string $namespace, string $location): string {
                if ('' === $location) {
                    return '';
                }
                return $namespace . ' ' . $location;
            },
            array_keys($this->pairs),
            $this->pairs
        )));
    }

    /** @return Traversable<string, string> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->pairs);
    }

    public function count(): int
    {
        return count($this->pairs);
    }
}
