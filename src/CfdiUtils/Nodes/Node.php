<?php

namespace CfdiUtils\Nodes;

use CfdiUtils\Utils\Xml;

class Node implements NodeInterface
{
    /** @var string */
    private $name;

    /** @var Attributes */
    private $attributes;

    /** @var Nodes|NodeInterface[] */
    private $children;

    /**
     * Node constructor.
     * @param string $name
     * @param array $attributes
     * @param NodeInterface[] $children
     */
    public function __construct(string $name, array $attributes = [], array $children = [])
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

    /**
     * @return Nodes|NodeInterface[]
     */
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

    public function clear()
    {
        $this->attributes->removeAll();
        $this->children()->removeAll();
    }

    public function addAttributes(array $attributes)
    {
        $this->attributes->importArray($attributes);
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

    public function searchNode(string ...$searchPath)
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

    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->attributes[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /*
     * other interfaces
     */

    /**
     * @return int
     */
    public function count()
    {
        return $this->children->count();
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return $this->children->getIterator();
    }
}
