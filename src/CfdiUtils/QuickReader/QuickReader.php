<?php
namespace CfdiUtils\QuickReader;

class QuickReader extends \stdClass implements \ArrayAccess
{
    /** @var string */
    protected $name;

    /** @var string[] */
    protected $attributes;

    /** @var self[] */
    protected $children;

    /**
     * QuickReader constructor.
     * @param string $name
     * @param string[] $attributes
     * @param self[] $children
     */
    public function __construct(string $name, array $attributes = [], array $children = [])
    {
        // TODO: validate name
        // TODO: validate children
        // TODO: validate attributes
        $this->name = $name;
        $this->attributes = $attributes;
        $this->children = $children;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self[]
     */
    public function __invoke(string $name = ''): array
    {
        if ('' === $name) {
            return $this->children;
        }
        return array_filter($this->children, function (QuickReader $item) use ($name) {
            return $this->namesAreEqual($name, (string) $item);
        });
    }

    public function __get(string $name): self
    {
        $child = $this->getChildByName($name);
        if (null === $child) {
            $child = new static($name);
        }

        return $child;
    }

    /**
     * @param string $name
     * @return self|null
     */
    protected function getChildByName(string $name)
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
        return $this->getChildByName($name) instanceof self;
    }

    /**
     * @param string $name
     * @return string|null
     */
    protected function getAttributeByName(string $name)
    {
        foreach ($this->attributes as $key => $value) {
            if ($this->namesAreEqual($name, $key)) {
                return $value;
            }
        }

        return null;
    }

    public function offsetExists($name): bool
    {
        // TODO: validar que sea name string
        return (null !== $this->getAttributeByName($name));
    }

    public function offsetGet($name): string
    {
        // TODO: validar que sea name string
        return $this->getAttributeByName($name) ?: '';
    }

    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Cannot change attributes');
    }

    public function offsetUnset($offset)
    {
        throw new \LogicException('Cannot change attributes');
    }

    protected function namesAreEqual(string $first, string $second): bool
    {
        return (0 === strcasecmp($first, $second));
    }
}
