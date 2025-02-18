<?php

namespace CfdiUtils\QuickReader;

class QuickReader extends \stdClass implements \ArrayAccess
{
    protected string $name;

    /** @var string[] */
    protected array $attributes;

    /** @var self[] */
    protected array $children;

    /**
     * QuickReader constructor.
     * @param string[] $attributes
     * @param self[] $children
     */
    public function __construct(string $name, array $attributes = [], array $children = [])
    {
        if ('' === $name) {
            throw new \LogicException('Property name cannot be empty');
        }
        foreach ($attributes as $key => $value) {
            if (! is_string($key) || '' === $key) {
                throw new \LogicException('There is an attibute with empty or non string name');
            }
            if (! is_string($value)) {
                throw new \LogicException("The attribute '$key' has a non string value");
            }
        }
        foreach ($children as $index => $child) {
            if (! $child instanceof static) {
                throw new \LogicException("The child $index is not an instance of " . static::class);
            }
        }
        $this->name = $name;
        $this->attributes = $attributes;
        $this->children = $children;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return self[]
     */
    public function __invoke(string $name = ''): array
    {
        if ('' === $name) {
            return $this->children;
        }
        return array_filter(
            $this->children,
            fn (self $item): bool => $this->namesAreEqual($name, (string) $item)
        );
    }

    /**
     * @return self
     */
    public function __get(string $name)
    {
        $child = $this->getChildByName($name);
        if (null === $child) {
            $child = new self($name);
        }

        return $child;
    }

    public function __set($name, $value)
    {
        throw new \LogicException('Cannot change children');
    }

    protected function getChildByName(string $name): ?self
    {
        foreach ($this->children as $child) {
            if ($this->namesAreEqual($name, (string) $child)) {
                return $child;
            }
        }

        return null;
    }

    public function __isset(string $name): bool
    {
        return $this->getChildByName($name) instanceof static;
    }

    protected function getAttributeByName(string $name): ?string
    {
        foreach ($this->attributes as $key => $value) {
            if ($this->namesAreEqual($name, $key)) {
                return $value;
            }
        }

        return null;
    }

    public function offsetExists($offset): bool
    {
        return (null !== $this->getAttributeByName((string) $offset));
    }

    public function offsetGet($offset): string
    {
        return $this->getAttributeByName((string) $offset) ?? '';
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Cannot change attributes');
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new \LogicException('Cannot change attributes');
    }

    protected function namesAreEqual(string $first, string $second): bool
    {
        return (0 === strcasecmp($first, $second));
    }
}
