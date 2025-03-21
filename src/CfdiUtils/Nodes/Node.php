<?php

namespace CfdiUtils\Nodes;

use CfdiUtils\Utils\Xml;
use Traversable;

class Node implements NodeInterface
{
    private string $name;

    private Attributes $attributes;

    /** @var Nodes|NodeInterface[] */
    private Nodes $children;

    /**
     * Node constructor.
     * @param NodeInterface[] $children
     */
    public function __construct(string $name, array $attributes = [], array $children = [], private string $value = '')
    {
        if (! Xml::isValidXmlName($name)) {
            throw new \UnexpectedValueException(sprintf('Cannot create a node with an invalid xml name: "%s"', $name));
        }
        $this->name = $name;
        $this->attributes = new Attributes($attributes);
        $this->children = new Nodes($children);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function children(): Nodes
    {
        return $this->children;
    }

    public function addChild(NodeInterface $node): NodeInterface
    {
        $this->children->add($node);
        return $node;
    }

    /**
     * @return Attributes|string[]
     */
    public function attributes(): Attributes
    {
        return $this->attributes;
    }

    public function clear(): void
    {
        $this->attributes->removeAll();
        $this->children()->removeAll();
    }

    public function addAttributes(array $attributes): void
    {
        $this->attributes->importArray($attributes);
    }

    public function exists(string $attribute): bool
    {
        return $this->attributes->exists($attribute);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /*
     * Search methods
     */

    public function searchAttribute(string ...$searchPath): string
    {
        $attribute = array_pop($searchPath);
        $node = $this->searchNode(...$searchPath);
        return (null !== $node) ? $node[$attribute] : '';
    }

    public function searchNodes(string ...$searchPath): Nodes
    {
        $nodes = new Nodes();
        $nodeName = array_pop($searchPath);
        $parent = $this->searchNode(...$searchPath);
        if (null !== $parent) {
            foreach ($parent->children() as $child) {
                if ($child->name() === $nodeName) {
                    $nodes->add($child);
                }
            }
        }
        return $nodes;
    }

    public function searchNode(string ...$searchPath): ?NodeInterface
    {
        $node = $this;
        foreach ($searchPath as $searchName) {
            $node = $node->children()->firstNodeWithName($searchName);
            if (null === $node) {
                break;
            }
        }
        return $node;
    }

    /*
     * Array access implementation as attribute helpers
     */

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return $this->exists(strval($offset));
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->attributes[$offset];
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value): void
    {
        $this->attributes[$offset] = $value;
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }

    /*
     * other interfaces
     */

    public function count(): int
    {
        return $this->children->count();
    }

    /** @return Traversable<NodeInterface> */
    public function getIterator(): Traversable
    {
        return $this->children->getIterator();
    }
}
