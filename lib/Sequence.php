<?php

namespace Liamduckett\Structures;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;
use Traversable;

/**
 * @template T of mixed
 *
 * @readonly
 *
 * @implements IteratorAggregate<int, T>
 */
final class Sequence implements Countable, IteratorAggregate
{
    /** @var list<T> */
    private array $items;

    // Creation ---

    /**
     * @param iterable<T> $items
     */
    public function __construct(iterable $items = [])
    {
        $results = [];

        foreach ($items as $item) {
            $results[] = $item;
        }

        $this->items = $results;
    }

    /**
     * @template TMake of mixed
     *
     * @param iterable<TMake> $items
     *
     * @return (TMake is never ? self<mixed> : self<TMake>)
     */
    public static function make(iterable $items = []): self
    {
        return new self($items);
    }

    // Adding ---

    /**
     * @param T $value
     */
    public function push(mixed $value): static
    {
        $items = $this->array();
        $items[] = $value;

        return new self($items);
    }

    /**
     * @param T $value
     */
    public function prepend(mixed $value): static
    {
        $items = $this->array();
        array_unshift($items, $value);

        return new self($items);
    }

    /**
     * @param iterable<T> $items
     */
    public function merge(iterable $items): static
    {
        $results = $this->items;

        foreach ($items as $item) {
            $results[] = $item;
        }

        return new self($results);
    }

    // Removing ---

    public function pop(): static
    {
        $items = $this->array();
        array_pop($items);

        return new self($items);
    }

    public function shift(): static
    {
        $items = $this->array();
        array_shift($items);

        return new self($items);
    }

    // Retrieving ---

    /**
     * @return T
     *
     * @throws OffsetDoesntExistException
     */
    public function get(int $index): mixed
    {
        if (!array_key_exists($index, $this->items)) {
            throw new OffsetDoesntExistException((string) $index);
        }

        return $this->items[$index];
    }

    /**
     * @return T
     *
     * @throws OffsetDoesntExistException
     */
    public function first(): mixed
    {
        return $this->get(0);
    }

    /**
     * @return T
     *
     * @throws OffsetDoesntExistException
     */
    public function last(): mixed
    {
        return $this->get($this->count() - 1);
    }

    public function count(): int
    {
        return count($this->items);
    }

    // Converting ---

    /**
     * @return list<T>
     */
    public function array(): array
    {
        return $this->items;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @return Traversable<int, T>
     */
    public function iterator(): Traversable
    {
        return $this->getIterator();
    }

    // Filtering ---

    /**
     * @param (callable(T, int): bool) $callable
     */
    public function filter(callable $callable): static
    {
        $results = array_values(array_filter(
            $this->items,
            $callable,
            ARRAY_FILTER_USE_BOTH,
        ));

        return new self($results);
    }

    // Mapping ---

    /**
     * @template TMap
     *
     * @param (callable(T, int): TMap) $callable
     *
     * @return self<TMap>
     */
    public function map(callable $callable): self
    {
        $results = [];

        foreach ($this as $index => $item) {
            $results[] = $callable($item, $index);
        }

        return new self($results);
    }

    // Containing ---

    /**
     * @param T $value
     */
    public function contains(mixed $value): bool
    {
        return in_array($value, $this->items, true);
    }

    /**
     * @param T $value
     */
    public function doesntContain(mixed $value): bool
    {
        return !$this->contains($value);
    }

    public function isEmpty(): bool
    {
        return [] === $this->items;
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    // Sorting ---

    public function sortAscending(): static
    {
        $copy = $this->items;
        sort($copy);

        return new self($copy);
    }

    public function sortDescending(): static
    {
        $copy = $this->items;
        rsort($copy);

        return new self($copy);
    }
}
