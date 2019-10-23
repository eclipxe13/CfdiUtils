<?php

namespace CfdiUtils\Validate;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Contracts\ValidatorInterface;

class MultiValidator implements ValidatorInterface, \Countable, \IteratorAggregate
{
    /** @var ValidatorInterface[] */
    private $validators = [];

    /** @var string */
    private $version;

    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        foreach ($this->validators as $validator) {
            if (! $validator->canValidateCfdiVersion($this->getVersion())) {
                continue;
            }
            $localAsserts = new Asserts();
            $validator->validate($comprobante, $localAsserts);
            $asserts->import($localAsserts);
            if ($localAsserts->mustStop()) {
                break;
            }
        }
    }

    public function canValidateCfdiVersion(string $version): bool
    {
        return ($this->version === $version);
    }

    public function hydrate(Hydrater $hydrater)
    {
        foreach ($this->validators as $validator) {
            $hydrater->hydrate($validator);
        }
    }

    /*
     * Collection methods
     */

    public function add(ValidatorInterface $validator)
    {
        $this->validators[] = $validator;
    }

    public function addMulti(ValidatorInterface ...$validators)
    {
        foreach ($validators as $validator) {
            $this->add($validator);
        }
    }

    public function exists(ValidatorInterface $validator): bool
    {
        return ($this->indexOf($validator) >= 0);
    }

    private function indexOf(ValidatorInterface $validator): int
    {
        $index = array_search($validator, $this->validators, true);
        return (false === $index) ? -1 : (int) $index;
    }

    public function remove(ValidatorInterface $validator)
    {
        $index = $this->indexOf($validator);
        if ($index >= 0) {
            unset($this->validators[$index]);
        }
    }

    public function removeAll()
    {
        $this->validators = [];
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->validators);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->validators);
    }
}
