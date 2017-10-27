<?php
namespace CfdiUtils\Nodes;

class Attributes implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /** @var string[] */
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
        $name = trim($name);
        if ('' === $name) {
            throw new \UnexpectedValueException('Cannot set an attribute without name');
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
            $this->set($key, $this->castValueToString($value));
        }
        return $this;
    }

    public function exportArray(): array
    {
        return $this->attributes;
    }

    /**
     * @param $value
     * @return string|null
     */
    private function castValueToString($value)
    {
        if (null === $value) {
            return null;
        }
        if (is_scalar($value) || is_object($value) && is_callable([$value, '__toString'])) {
            return (string) $value;
        }
        throw new \InvalidArgumentException('attribute value cannot be converted to string');
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->attributes);
    }

    public function offsetExists($offset)
    {
        return $this->exists((string) $offset);
    }

    public function offsetGet($offset)
    {
        return $this->get((string) $offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set((string) $offset, $this->castValueToString($value));
    }

    public function offsetUnset($offset)
    {
        $this->remove((string) $offset);
    }

    public function count()
    {
        return count($this->attributes);
    }
}
