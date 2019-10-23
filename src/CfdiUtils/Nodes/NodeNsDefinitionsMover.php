<?php

namespace CfdiUtils\Nodes;

use CfdiUtils\Utils\SchemaLocations;

class NodeNsDefinitionsMover
{
    /** @var callable|null */
    private $namespaceFilter;

    public function __construct()
    {
        $this->setNamespaceFilter(null);
    }

    public function hasNamespaceFilter(): bool
    {
        return (null !== $this->namespaceFilter);
    }

    /**
     * @return callable|null
     */
    public function getNamespaceFilter()
    {
        return $this->namespaceFilter;
    }

    public function setNamespaceFilter(callable $filter = null): self
    {
        $this->namespaceFilter = $filter;
        return $this;
    }

    public function process(NodeInterface $root)
    {
        $rootSchemaLocation = SchemaLocations::fromString($root['xsi:schemaLocation'], false);
        $this->processRecursive($root->children(), $root, $rootSchemaLocation);
        if (! $rootSchemaLocation->isEmpty()) {
            $root['xsi:schemaLocation'] = $rootSchemaLocation->asString();
        }
    }

    protected function processRecursive(Nodes $children, NodeInterface $root, SchemaLocations $schemaLocations)
    {
        /** @var NodeInterface $child */
        foreach ($children as $child) {
            $this->moveXmlNs($child, $root);
            $this->moveXsiSchemaLocation($child, $schemaLocations);
            $this->processRecursive($child->children(), $root, $schemaLocations);
        }
    }

    protected function moveXmlNs(NodeInterface $child, NodeInterface $root)
    {
        $prefix = explode(':', $child->name(), 2)[0] ?? '';
        if ($child->name() === $prefix) {
            return;  // it does not have a prefix
        }
        $xmlns = 'xmlns:' . $prefix;
        if (! isset($child[$xmlns])) {
            return; // it does not have a definition
        }
        if (! $this->filterNamespace($child[$xmlns])) {
            return; // it did not pass the namespace filter
        }
        $root->addAttributes([$xmlns => $child[$xmlns]]);
        $child->addAttributes([$xmlns => null]);
    }

    protected function moveXsiSchemaLocation(NodeInterface $child, SchemaLocations $rootSchemaLocations)
    {
        if (! isset($child['xsi:schemaLocation'])) {
            return;
        }
        $childSchemaLocations = SchemaLocations::fromString($child['xsi:schemaLocation'], false);
        foreach ($childSchemaLocations as $namespace => $location) {
            if (! $this->filterNamespace($namespace)) {
                continue;
            }
            if (! $rootSchemaLocations->has($namespace)) {
                $rootSchemaLocations->append($namespace, $location);
            }
        }
        foreach ($rootSchemaLocations as $namespace => $location) {
            $childSchemaLocations->remove($namespace);
        }
        $child->addAttributes([
            'xsi:schemaLocation' => $childSchemaLocations->isEmpty() ? null : $childSchemaLocations->asString(),
        ]);
    }

    public function filterNamespace(string $namespace): bool
    {
        if (! is_callable($this->namespaceFilter)) {
            return true;
        }
        return boolval(call_user_func($this->namespaceFilter, $namespace));
    }
}
