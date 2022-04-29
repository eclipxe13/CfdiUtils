<?php

namespace CfdiUtils\Nodes;

interface NodeInterface extends \ArrayAccess, \Countable, \IteratorAggregate
{
    public function name(): string;

    /**
     * @return Nodes|NodeInterface[]
     */
    public function children(): Nodes;

    public function addChild(self $node): self;

    public function attributes(): Attributes;

    public function addAttributes(array $attributes);

    public function clear();

    public function searchAttribute(string ...$searchPath): string;

    /**
     * @param string ...$searchPath
     * @return Nodes|NodeInterface[]
     */
    public function searchNodes(string ...$searchPath): Nodes;

    /**
     * @param string ...$searchPath
     * @return NodeInterface|null
     */
    public function searchNode(string ...$searchPath);
}
