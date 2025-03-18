<?php

namespace CfdiUtils\Validate;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use Traversable;

class MultiValidator implements ValidatorInterface, \Countable, \IteratorAggregate
{
    /** @var ValidatorInterface[] */
    private array $validators = [];

    public function __construct(private string $version)
    {
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts): void
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

    public function hydrate(Hydrater $hydrater): void
    {
        foreach ($this->validators as $validator) {
            $hydrater->hydrate($validator);
        }
    }

    /*
     * Collection methods
     */

    public function add(ValidatorInterface $validator): void
    {
        $this->validators[] = $validator;
    }

    public function addMulti(ValidatorInterface ...$validators): void
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

    public function remove(ValidatorInterface $validator): void
    {
        $index = $this->indexOf($validator);
        if ($index >= 0) {
            unset($this->validators[$index]);
        }
    }

    public function removeAll(): void
    {
        $this->validators = [];
    }

    /** @return Traversable<ValidatorInterface> */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->validators);
    }

    public function count(): int
    {
        return count($this->validators);
    }
}
