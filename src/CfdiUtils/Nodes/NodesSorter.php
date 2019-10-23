<?php

namespace CfdiUtils\Nodes;

/**
 * This class is used to sort elements by name in a Nodes collection
 * @internal
 */
class NodesSorter
{
    /** @var array<string, int> array of key (string) value (int) representing the naming order */
    private $order;

    /** @var int */
    private $count;

    public function __construct(array $order = [])
    {
        $this->setOrder($order);
    }

    /**
     * It takes only the unique string names and sort using the order of appearance
     * @param string[] $names
     * @return bool true if the new names list is different from previous
     */
    public function setOrder(array $names): bool
    {
        $order = array_flip($this->parseNames($names));
        if ($this->order === $order) {
            return false;
        }
        $this->order = $order;
        $this->count = count($order);
        return true;
    }

    /**
     * @param array $names
     * @return string[]
     */
    public function parseNames(array $names): array
    {
        $isValidName = function ($name): bool {
            return is_string($name) && (bool) $name;
        };
        return array_values(array_unique(array_filter($names, $isValidName)));
    }

    /**
     * The current order list
     * @return string[]
     */
    public function getOrder(): array
    {
        return (array) array_flip($this->order);
    }

    /**
     * @param NodeInterface[] $nodes
     * @return NodeInterface[]
     */
    public function sort(array $nodes): array
    {
        if ($this->count > 0) {
            // do not use simple usort since usort is not "stable"
            // usort does not respect the previous order.
            $nodes = $this->stableArraySort($nodes, [$this, 'compareNodesByName']);
        }
        return $nodes;
    }

    public function compareNodesByName(NodeInterface $a, NodeInterface $b)
    {
        return $this->valueByName($a->name()) <=> $this->valueByName($b->name());
    }

    public function valueByName(string $name): int
    {
        return $this->order[$name] ?? $this->count;
    }

    /*
     * This function is a replacement for usort that try to usort
     * but if items are equal then uses the relative position as second argument
     */
    private function stableArraySort(array $input, callable $function): array
    {
        // create the item list with the item and the index
        $list = [];
        foreach (array_values($input) as $i => $value) {
            $item = new \stdClass();
            $item->item = $value;
            $item->index = $i;
            $list[] = $item;
        }

        // perform the usort, if comparison is equal then compare the index also
        usort($list, function (\stdClass $first, \stdClass $second) use ($function) {
            $value = $function($first->item, $second->item);
            if (0 === $value) {
                $value = $first->index <=> $second->index;
            }
            return $value;
        });

        // return only the items
        return array_column($list, 'item');
    }
}
