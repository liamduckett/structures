<?php

namespace Liamduckett\Structures;

use IteratorAggregate;
use Liamduckett\Structures\Concerns\BuildsTypedIterator;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;
use Traversable;

/**
 * @template T of mixed
 *
 * @readonly
 *
 * @implements IteratorAggregate<non-empty-string, T>
 */
final class Dictionary implements IteratorAggregate
{
    use BuildsTypedIterator;

    /** @var array<non-empty-string, T> */
    private array $items;

    // Creation ---

    /**
     * @param iterable<non-empty-string, T> $items
     */
    public function __construct(iterable $items = [])
    {
        $this->items = is_array($items) ? $items : iterator_to_array($items);
    }

    /**
     * @template TMake of mixed
     *
     * @param iterable<non-empty-string, TMake> $items
     *
     * @return (TMake is never ? self<mixed> : self<TMake>)
     */
    public static function make(iterable $items = []): self
    {
        return new self($items);
    }

    // Adding ---

    /**
     * @param non-empty-string $key
     * @param T                $value
     */
    public function set(mixed $key, mixed $value): static
    {
        $items = $this->array();
        $items[$key] = $value;

        return new self($items);
    }

    /**
     * @param iterable<non-empty-string, T> $items
     */
    public function merge(iterable $items): static
    {
        $results = [];

        foreach ($this->items as $key => $item) {
            $results[$key] = $item;
        }

        foreach ($items as $key => $item) {
            $results[$key] = $item;
        }

        return new self($results);
    }

    // Removing ---

    public function remove(string $key): static
    {
        $items = $this->array();
        unset($items[$key]);

        return new self($items);
    }

    // Retrieving ---

    /**
     * @param non-empty-string $key
     *
     * @return T
     *
     * @throws OffsetDoesntExistException
     */
    public function get(string $key): mixed
    {
        if (!array_key_exists($key, $this->items)) {
            throw new OffsetDoesntExistException($key);
        }

        return $this->items[$key];
    }

    /**
     * @return iterable<non-empty-string>
     */
    public function keys(): iterable
    {
        return array_keys($this->items);
    }

    /**
     * @return iterable<non-empty-string, T>
     */
    public function items(): iterable
    {
        return $this->items;
    }

    /**
     * @return iterable<T>
     */
    public function values(): iterable
    {
        return array_values($this->items);
    }

    // Converting ---

    /**
     * @return array<non-empty-string, T>
     */
    public function array(): array
    {
        return $this->items;
    }

    public function getIterator(): Traversable
    {
        return $this->buildTypedIterator($this->items);
    }

    /**
     * @return Traversable<non-empty-string, T>
     */
    public function iterator(): Traversable
    {
        return $this->getIterator();
    }

    // Filtering ---

    /**
     * @param (callable(T, non-empty-string): bool) $callable
     */
    public function filter(callable $callable): static
    {
        $results = array_filter(
            $this->items,
            $callable,
            ARRAY_FILTER_USE_BOTH,
        );

        return new self($results);
    }

    /**
     * @param T $value
     *
     * @return iterable<non-empty-string>
     */
    public function search(mixed $value): iterable
    {
        $results = [];

        foreach ($this->items as $key => $item) {
            if ($item === $value) {
                $results[] = $key;
            }
        }

        return $results;
    }

    // Mapping ---

    /**
     * @template TMap
     *
     * @param (callable(T, non-empty-string): TMap) $callable
     *
     * @return self<TMap>
     */
    public function map(callable $callable): self
    {
        $results = [];

        foreach ($this as $key => $item) {
            $results[$key] = $callable($item, $key);
        }

        return new self($results);
    }

    // Containing ---

    /**
     * @param non-empty-string $key
     */
    public function containsKey(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @param T $value
     */
    public function containsValue(mixed $value): bool
    {
        return in_array($value, $this->items, true);
    }

    /**
     * @param non-empty-string $key
     */
    public function doesntContainKey(string $key): bool
    {
        return !$this->containsKey($key);
    }

    /**
     * @param T $value
     */
    public function doesntContainValue(mixed $value): bool
    {
        return !$this->containsValue($value);
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

    public function sortKeyAscending(): static
    {
        $copy = $this->items;

        ksort($copy);

        return new self($copy);
    }
}
