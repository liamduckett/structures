<?php

namespace Liamduckett\Structures;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Liamduckett\Structures\Concerns\HasArrayAccess;

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

    // Creation ---

    /**
     * @param iterable<non-empty-string, T> $items
     */
    public function __construct(iterable $items = [])
    {
        $this->items = is_array($items) ? $items : iterator_to_array($items);
    }

    /**
     * @param iterable<non-empty-string, T> $items
     */
    public static function make(iterable $items = []): static
    {
        return new self($items);
    }

    // Additions ---

    /**
     * @param iterable<non-empty-string, T> $items
     */
    public function merge(iterable $items): static
    {
        $results = [];

        foreach ($items as $key => $item) {
            $results[$key] = $item;
        }

        foreach ($this->items as $key => $item) {
            $results[$key] = $item;
        }

        return new self($results);
    }

    // Conversions ---

    /**
     * @return iterable<non-empty-string, T>
     */
    public function all(): iterable
    {
        return $this->items;
    }

    public function getIterator(): iterable
    {
        /**
         * https://github.com/phpstan/phpstan/issues/10289.
         *
         * @var ArrayIterator<non-empty-string, T> $iterator
         */
        $iterator = new ArrayIterator($this->items);

        return $iterator;
    }

    /**
     * @return iterable<non-empty-string>
     */
    public function keys(): iterable
    {
        $results = [];

        foreach ($this->items as $key => $_) {
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

        foreach ($this->items as $item) {
            $results[] = $item;
        }

        return $results;
    }

    // Filtering ---

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

    // Presence ---

    /**
     * @param non-empty-string $key
     */
    public function containsKey(string $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * @param T $value
     */
    public function containsValue(mixed $value): bool
    {
        foreach ($this->items as $item) {
            if ($item === $value) {
                return true;
            }
        }

        return false;
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
        foreach ($this->items as $_) {
            return false;
        }

        return true;
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
