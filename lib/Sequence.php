<?php

namespace Liamduckett\Structures;

use Closure;
use Countable;
use Generator;
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
    /** @var Closure(): Generator<int, T, mixed, void> */
    private Closure $factory;

    // Creation ---

    /**
     * @param (Closure(): Generator<int, T, mixed, void>)|iterable<T> $items
     */
    public function __construct(Closure|iterable $items = [])
    {
        $this->factory = $items instanceof Closure
            ? $items
            : static function () use ($items): Generator {
                yield from iterable_values($items);
            };
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
     * @param iterable<T> $items
     */
    public function push(iterable $items): static
    {
        return $this->merge($items);
    }

    /**
     * @param iterable<T> $items
     */
    public function prepend(iterable $items): static
    {
        $self = new self($items);

        return $self->merge($this);
    }

    /**
     * @param iterable<T> $items
     */
    public function merge(iterable $items): static
    {
        return new self(
            function () use ($items): Generator {
                foreach ($this->iterator() as $item) {
                    yield $item;
                }

                foreach ($items as $item) {
                    yield $item;
                }
            }
        );
    }

    // Removing ---

    /**
     * @param non-negative-int  $offset
     * @param null|positive-int $length
     */
    public function slice(int $offset, ?int $length = null): static
    {
        return new self(function () use ($offset, $length): Generator {
            $index = 0;
            $extracted = 0;

            foreach ($this->iterator() as $item) {
                if ($index++ < $offset) {
                    continue;
                }

                if (null !== $length && $extracted >= $length) {
                    break;
                }

                yield $item;
                ++$extracted;
            }
        });
    }

    // Retrieving ---

    /**
     * @return T
     *
     * @throws OffsetDoesntExistException
     */
    public function get(int $index): mixed
    {
        return iterable_get($this->iterator(), $index);
    }

    /**
     * @return T
     *
     * @throws OffsetDoesntExistException
     */
    public function first(): mixed
    {
        return iterable_first($this->iterator());
    }

    /**
     * @return T
     *
     * @throws OffsetDoesntExistException
     */
    public function last(): mixed
    {
        return iterable_last($this->iterator());
    }

    public function count(): int
    {
        return iterable_count($this->iterator());
    }

    // Converting ---

    /**
     * @return list<T>
     */
    public function array(): array
    {
        return iterator_to_array($this->iterator(), false);
    }

    public function getIterator(): Traversable
    {
        return ($this->factory)();
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
     * @param callable(T, int): bool $callable
     */
    public function filter(callable $callable): static
    {
        return new self(function () use ($callable): Generator {
            foreach ($this->iterator() as $index => $item) {
                if (true === $callable($item, $index)) {
                    yield $item;
                }
            }
        });
    }

    // Mapping ---

    /**
     * @template TMap
     *
     * @param callable(T, int): TMap $callable
     *
     * @return self<TMap>
     */
    public function map(callable $callable): self
    {
        return new self(function () use ($callable): Generator {
            yield from iterable_map($this->iterator(), $callable);
        });
    }

    // Chunking ---

    /**
     * @param positive-int $size
     *
     * @return self<self<T>>
     */
    public function chunk(int $size): self
    {
        return new self(function () use ($size): Generator {
            foreach (iterable_chunk_values($this->iterator(), $size) as $chunk) {
                yield new self($chunk);
            }
        });
    }

    // Containing ---

    /**
     * @param T $value
     */
    public function contains(mixed $value): bool
    {
        return iterable_contains($this->iterator(), $value);
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
        return iterable_empty($this->iterator());
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }
}
