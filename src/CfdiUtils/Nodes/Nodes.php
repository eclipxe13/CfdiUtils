<?php

namespace CfdiUtils\Nodes;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, NodeInterface>
 */
class Nodes implements Countable, IteratorAggregate
{
    /** @var NodeInterface[] */
    private array $nodes = [];

    private NodesSorter $sorter;

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

    public function order(): void
    {
        $this->nodes = $this->sorter->sort($this->nodes);
    }

    /**
     * It takes only the unique string names and sort using the order of appearance
     * @param string[] $names
     */
    public function setOrder(array $names): void
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
        return $this->indexOf($node) >= 0;
    }

    public function first(): ?NodeInterface
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

    public function firstNodeWithName(string $nodeName): ?NodeInterface
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

    /** @return Traversable<int, NodeInterface> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->nodes);
    }

    public function count(): int
    {
        return count($this->nodes);
    }
}
