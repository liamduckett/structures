<?php

namespace Liamduckett\Structures;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Liamduckett\Structures\Concerns\HasArrayAccess;
use Traversable;

/**
 * @template T of mixed
 *
 * @implements ArrayAccess<non-empty-string, T>
 * @implements IteratorAggregate<non-empty-string, T>
 */
final class Dictionary implements ArrayAccess, IteratorAggregate
{
    /** @use HasArrayAccess<non-empty-string, T> */
    use HasArrayAccess;

    /** @var array<non-empty-string, T> */
    protected array $items = [];

    /**
     * @param iterable<non-empty-string, T> $items
     */
    public function __construct(iterable $items = [])
    {
        $this->items = is_array($items) ? $items : iterator_to_array($items);
    }

    /**
     * @return iterable<non-empty-string, T>
     */
    public function all(): iterable
    {
        return $this->items;
    }

    /**
     * @param (callable(T, non-empty-string): bool) $callable
     */
    public function filter(callable $callable): static
    {
        $results = [];

        foreach ($this as $key => $item) {
            if ($callable($item, $key)) {
                $results[$key] = $item;
            }
        }

        return new self($results);
    }

    /**
     * TODO:
     *  why doesnt this work: https://phpstan.org/r/f4e17f73-e3dc-4712-a630-8a7184d37653
     *  but this does: https://phpstan.org/r/1f38ce37-e4ba-40bb-896e-8989e294483c
     */
    public function getIterator(): iterable
    {
        return new ArrayIterator($this->items);
    }

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

    public function sortKeyAscending(): static
    {
        $copy = $this->items;

        ksort($copy);

        return new self($copy);
    }

    /**
     * @param T $value
     */
    public function containsValue(mixed $value): bool
    {
        foreach($this->items as $item) {
            if($item === $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param T $value
     */
    public function doesntContainValue(mixed $value): bool
    {
        return ! $this->containsValue($value);
    }

    /**
     * @param non-empty-string $key
     */
    public function containsKey(string $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * @param non-empty-string $key
     */
    public function doesntContainKey(string $key): bool
    {
        return ! $this->containsKey($key);
    }

    /**
     * @param iterable<non-empty-string, T> $items
     */
    public function merge(iterable $items): static
    {
        $results = [];

        foreach($items as $key => $item) {
            $results[$key] = $item;
        }

        foreach($this->items as $key => $item) {
            $results[$key] = $item;
        }

        return new self($results);
    }

    public function isEmpty(): bool
    {
        foreach($this->items as $_) {
            return true;
        }

        return false;
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * @return iterable<non-empty-string>
     */
    public function keys(): iterable
    {
        $results = [];

        foreach($this->items as $key => $_) {
            $results[] = $key;
        }

        return $results;
    }

    /**
     * @return iterable<T>
     */
    public function values(): iterable
    {
        $results = [];

        foreach($this->items as $item) {
            $results[] = $item;
        }

        return $results;
    }

    /**
     * @param T $value
     *
     * @return iterable<non-empty-string>
     */
    public function search(mixed $value): iterable
    {
        $results = [];

        foreach($this->items as $key => $item) {
            if($item === $value) {
                $results[] = $key;
            }
        }

        return $results;
    }

    ///**
    // * @param positive-int $count
    // *
    // * @return iterable<static>
    // */
    //public function chunk(int $count): iterable
    //{
    //
    //}
}
