<?php

namespace CfdiUtils\Nodes;

class Nodes implements \Countable, \IteratorAggregate
{
    /** @var NodeInterface[] */
    private $nodes = [];

    /** @var NodesSorter */
    private $sorter;

    /**
     * Nodes constructor.
     * @param NodeInterface[] $nodes
     */
    public function __construct(array $nodes = [])
    {
        $this->sorter = new NodesSorter();
        $this->importFromArray($nodes);
    }

    public function add(NodeInterface ...$nodes): self
    {
        $somethingChange = false;
        foreach ($nodes as $node) {
            if (! $this->exists($node)) {
                $this->nodes[] = $node;
                $somethingChange = true;
            }
        }
        if ($somethingChange) {
            $this->order();
        }
        return $this;
    }

    public function order()
    {
        $this->nodes = $this->sorter->sort($this->nodes);
    }

    /**
     * It takes only the unique string names and sort using the order of appearance
     * @param string[] $names
     */
    public function setOrder(array $names)
    {
        if ($this->sorter->setOrder($names)) {
            $this->order();
        }
    }

    /** @return string[] */
    public function getOrder(): array
    {
        return $this->sorter->getOrder();
    }

    public function indexOf(NodeInterface $node): int
    {
        if (false === $index = array_search($node, $this->nodes, true)) {
            $index = -1;
        }
        return (int) $index;
    }

    public function remove(NodeInterface $node): self
    {
        $index = $this->indexOf($node);
        if ($index >= 0) {
            unset($this->nodes[$index]);
        }
        return $this;
    }

    public function removeAll(): self
    {
        $this->nodes = [];
        return $this;
    }

    public function exists(NodeInterface $node): bool
    {
        return ($this->indexOf($node) >= 0);
    }

    /**
     * @return NodeInterface|null
     */
    public function first()
    {
        foreach ($this->nodes as $node) {
            return $node;
        }
        return null;
    }

    public function get(int $position): NodeInterface
    {
        $indexedNodes = array_values($this->nodes);
        if (! array_key_exists($position, $indexedNodes)) {
            throw new \OutOfRangeException("The index $position does not exists");
        }
        return $indexedNodes[$position];
    }

    /**
     * @param string $nodeName
     * @return NodeInterface|null
     */
    public function firstNodeWithName(string $nodeName)
    {
        foreach ($this->nodes as $node) {
            if ($node->name() === $nodeName) {
                return $node;
            }
        }
        return null;
    }

    public function getNodesByName(string $nodeName): self
    {
        $nodes = new self();
        foreach ($this->nodes as $node) {
            if ($node->name() === $nodeName) {
                $nodes->add($node);
            }
        }
        return $nodes;
    }

    /**
     * @param NodeInterface[] $nodes
     * @return Nodes
     */
    public function importFromArray(array $nodes): self
    {
        foreach ($nodes as $index => $node) {
            if (! ($node instanceof NodeInterface)) {
                throw new \InvalidArgumentException("The element index $index is not a NodeInterface object");
            }
        }
        $this->add(...$nodes);
        return $this;
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->nodes);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->nodes);
    }
}
