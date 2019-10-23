<?php

namespace CfdiUtils\Validate;

use CfdiUtils\Validate\Contracts\DiscoverableCreateInterface as Discoverable;
use CfdiUtils\Validate\Contracts\ValidatorInterface;

class Discoverer
{
    public function castNamespacePrefix(string $namespacePrefix): string
    {
        return rtrim($namespacePrefix, '\\') . '\\';
    }

    /**
     * @param string $namespacePrefix
     * @param string $directoryPath
     * @return ValidatorInterface[]
     */
    public function discoverInFolder(string $namespacePrefix, string $directoryPath): array
    {
        $discovered = [];
        $filenames = glob($directoryPath . '/*.php') ?: [];
        foreach ($filenames as $filename) {
            $object = $this->discoverInFile($namespacePrefix, $filename);
            if (null !== $object) {
                $discovered[] = $object;
            }
        }
        return $discovered;
    }

    /**
     * @param string $namespacePrefix
     * @param string $filename
     * @return ValidatorInterface|null
     */
    public function discoverInFile(string $namespacePrefix, string $filename)
    {
        $basename = basename($filename);
        $classname = $this->castNamespacePrefix($namespacePrefix) . substr($basename, 0, strlen($basename) - 4);
        if (class_exists($classname) && in_array(Discoverable::class, class_implements($classname), true)) {
            /** @var callable $callable */
            $callable = [$classname, 'createDiscovered'];
            $object = call_user_func($callable);
            if ($object instanceof ValidatorInterface) {
                return $object;
            }
        }
        return null;
    }
}
