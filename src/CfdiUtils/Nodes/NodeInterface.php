<?php

namespace CfdiUtils\Nodes;

interface NodeInterface extends \ArrayAccess, \Countable, \IteratorAggregate
{
    public function name(): string;

    public function children(): Nodes;

    public function addChild(self $node): self;

    public function attributes(): Attributes;

    public function addAttributes(array $attributes);

    public function exists(string $attribute): bool;

    public function value(): string;

    public function setValue(string $value): void;

    public function clear();

    public function searchAttribute(string ...$searchPath): string;

    public function searchNodes(string ...$searchPath): Nodes;

    public function searchNode(string ...$searchPath): ?self;
}
