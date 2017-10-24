<?php
namespace CfdiUtils\Nodes;

interface NodeInterface extends \ArrayAccess, \Countable, \IteratorAggregate
{
    public function name(): string;

    public function children(): Nodes;

    public function addChild(Node $node);

    public function attributes(): Attributes;

    public function addAttributes(array $attributes);

    public function clear();

    public function searchAttribute(string ...$searchPath): string;

    public function searchNodes(string ...$searchPath): Nodes;

    /**
     * @param string[] ...$searchPath
     * @return Node|null
     */
    public function searchNode(string ...$searchPath);
}
