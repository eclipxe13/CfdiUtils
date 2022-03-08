<?php

namespace CfdiUtils\Nodes;

use CfdiUtils\Utils\Xml;
use Traversable;

class Attributes implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /** @var array<string, string> */
    private $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->importArray($attributes);
    }

    public function get(string $name): string
    {
        if (! array_key_exists($name, $this->attributes)) {
            return '';
        }
        return $this->attributes[$name];
    }

    /**
     * Set a value in the collection
     *
     * @param string $name
     * @param string|null $value If null then it will remove the value instead of setting to empty string
     * @return self
     */
    public function set(string $name, string $value = null): self
    {
        if (null === $value) {
            $this->remove($name);
            return $this;
        }
        if (! Xml::isValidXmlName($name)) {
            throw new \UnexpectedValueException(sprintf('Cannot set attribute with an invalid xml name: "%s"', $name));
        }
        $this->attributes[$name] = $value;
        return $this;
    }

    public function remove(string $name): self
    {
        unset($this->attributes[$name]);
        return $this;
    }

    public function removeAll(): self
    {
        $this->attributes = [];
        return $this;
    }

    public function exists(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    public function importArray(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->set($key, $this->castValueToString($key, $value));
        }
        return $this;
    }

    public function exportArray(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return string|null
     */
    private function castValueToString(string $key, $value)
    {
        if (null === $value) {
            return null;
        }
        if (is_scalar($value)) {
            return strval($value);
        }
        if (is_object($value) && is_callable([$value, '__toString'])) {
            return strval($value);
        }
        throw new \InvalidArgumentException(sprintf('Cannot convert value of attribute %s to string', $key));
    }

    /** @return Traversable<string, string> */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->attributes);
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return $this->exists((string) $offset);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->get((string) $offset);
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $offset = strval($offset);
        $this->set($offset, $this->castValueToString($offset, $value));
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        $this->remove((string) $offset);
    }

    public function count(): int
    {
        return count($this->attributes);
    }
}
