<?php

namespace CfdiUtils\Validate;

use Traversable;

class Asserts implements \Countable, \IteratorAggregate
{
    /** @var array<string, Assert> */
    private array $asserts = [];

    private bool $mustStop = false;

    /**
     * This will try to create a new assert or get and change an assert with the same code
     * The new values are preserved, except if they are null
     */
    public function put(
        string $code,
        ?string $title = null,
        ?Status $status = null,
        ?string $explanation = null
    ): Assert {
        if (! $this->exists($code)) {
            $assert = new Assert($code, (string) $title, $status, (string) $explanation);
            $this->add($assert);
            return $assert;
        }
        $assert = $this->get($code);
        if (null !== $title) {
            $assert->setTitle($title);
        }
        if (null !== $status) {
            $assert->setStatus($status);
        }
        if (null !== $explanation) {
            $assert->setExplanation($explanation);
        }
        return $assert;
    }

    /**
     * This will try to create a new assert or get and change an assert with the same code
     * The new values are preserved, except if they are null
     */
    public function putStatus(string $code, ?Status $status = null, ?string $explanation = null): Assert
    {
        return $this->put($code, null, $status, $explanation);
    }

    /**
     * Get and or set the flag that alerts about stop flow
     * Consider this flag as: "Something was found, you must not continue"
     *
     * @param bool|null $newValue value of the flag, if null then will not change the flag
     * @return bool the previous value of the flag
     */
    public function mustStop(?bool $newValue = null): bool
    {
        if (null === $newValue) {
            return $this->mustStop;
        }
        $previous = $this->mustStop;
        $this->mustStop = $newValue;
        return $previous;
    }

    public function hasStatus(Status $status): bool
    {
        return (null !== $this->getFirstStatus($status));
    }

    public function hasErrors(): bool
    {
        return $this->hasStatus(Status::error());
    }

    public function hasWarnings(): bool
    {
        return $this->hasStatus(Status::warn());
    }

    public function getFirstStatus(Status $status): ?Assert
    {
        foreach ($this->asserts as $assert) {
            if ($status->equalsTo($assert->getStatus())) {
                return $assert;
            }
        }
        return null;
    }

    /**
     * @return Assert[]
     */
    public function byStatus(Status $status): array
    {
        return array_filter($this->asserts, fn (Assert $item): bool => $status->equalsTo($item->getStatus()));
    }

    public function get(string $code): Assert
    {
        foreach ($this->asserts as $assert) {
            if ($assert->getCode() === $code) {
                return $assert;
            }
        }
        throw new \RuntimeException("There is no assert with code $code");
    }

    public function exists(string $code): bool
    {
        return array_key_exists($code, $this->asserts);
    }

    /**
     * @return Assert[]
     */
    public function oks(): array
    {
        return $this->byStatus(Status::ok());
    }

    /**
     * @return Assert[]
     */
    public function errors(): array
    {
        return $this->byStatus(Status::error());
    }

    /**
     * @return Assert[]
     */
    public function warnings(): array
    {
        return $this->byStatus(Status::warn());
    }

    /**
     * @return Assert[]
     */
    public function nones(): array
    {
        return $this->byStatus(Status::none());
    }

    public function add(Assert $assert): void
    {
        $this->asserts[$assert->getCode()] = $assert;
    }

    private function indexOf(Assert $assert): string
    {
        $index = array_search($assert, $this->asserts, true);
        return (false === $index) ? '' : $index;
    }

    public function remove(Assert $assert): void
    {
        $index = $this->indexOf($assert);
        if ('' !== $index) {
            unset($this->asserts[$index]);
        }
    }

    public function removeByCode(string $index): void
    {
        unset($this->asserts[$index]);
    }

    public function removeAll(): void
    {
        $this->asserts = [];
    }

    public function import(self $asserts): void
    {
        foreach ($asserts as $assert) {
            $this->add(clone $assert);
        }
        $this->mustStop($asserts->mustStop());
    }

    /** @return Traversable<string, Assert> */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->asserts);
    }

    public function count(): int
    {
        return count($this->asserts);
    }
}
