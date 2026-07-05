<?php

namespace Liamduckett\Structures;

use Closure;
use Generator;
use IteratorAggregate;
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
    /** @var Closure(): Generator<non-empty-string, T, mixed, void> */
    private Closure $factory;

    // Creation ---

    /**
     * @param (Closure(): Generator<non-empty-string, T, mixed, void>)|iterable<non-empty-string, T> $items
     */
    public function __construct(Closure|iterable $items = [])
    {
        $this->factory = $items instanceof Closure
            ? $items
            : static function () use ($items): Generator {
                yield from $items;
            };
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
        return new self(function () use ($key, $value): Generator {
            $found = false;

            foreach ($this->iterator() as $existingKey => $existingValue) {
                if ($existingKey === $key) {
                    $found = true;

                    yield $existingKey => $value;

                    continue;
                }

                yield $existingKey => $existingValue;
            }

            if (!$found) {
                yield $key => $value;
            }
        });
    }

    // Chunking ---

    /**
     * @param positive-int $size
     *
     * @return Sequence<self<T>>
     */
    public function chunk(int $size): Sequence
    {
        return new Sequence(function () use ($size): Generator {
            foreach (iterable_chunk($this->iterator(), $size) as $chunk) {
                yield new self($chunk);
            }
        });
    }

    // Containing ---

    /**
     * @return ($key is non-empty-string ? bool : false)
     */
    public function containsKey(string $key): bool
    {
        return iterable_contains_key($this->iterator(), $key);
    }

    /**
     * @param T $value
     */
    public function containsValue(mixed $value): bool
    {
        return iterable_contains($this->iterator(), $value);
    }

    /**
     * @return ($key is non-empty-string ? bool : true)
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
        return iterable_empty($this->iterator());
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    // Converting ---

    /**
     * @return array<non-empty-string, T>
     */
    public function array(): array
    {
        return iterable_to_array($this->iterator());
    }

    public function getIterator(): Traversable
    {
        return ($this->factory)();
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
        return new self(function () use ($callable): Generator {
            yield from iterable_filter($this->iterator(), $callable);
        });
    }

    /**
     * @param T $value
     *
     * @return Sequence<non-empty-string>
     */
    public function search(mixed $value): Sequence
    {
        return new Sequence(function () use ($value): Generator {
            yield from iterable_search($this->iterator(), $value);
        });
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
        return new self(function () use ($callable): Generator {
            yield from iterable_map($this->iterator(), $callable);
        });
    }

    // Removing ---

    public function remove(string $key): static
    {
        return $this->filter(static fn (mixed $_, string $currentKey) => $currentKey !== $key);
    }

    // Retrieving ---

    /**
     * @return T
     *
     * @throws OffsetDoesntExistException
     */
    public function get(string $key): mixed
    {
        return iterable_get($this->iterator(), $key);
    }

    /**
     * @return Sequence<non-empty-string>
     */
    public function keys(): Sequence
    {
        return new Sequence(function (): Generator {
            yield from iterable_keys($this->iterator());
        });
    }

    /**
     * @return Sequence<T>
     */
    public function values(): Sequence
    {
        return new Sequence(function (): Generator {
            yield from iterable_values($this->iterator());
        });
    }
}
