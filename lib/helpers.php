<?php

namespace Liamduckett\Structures;

use Closure;
use Countable;
use Generator;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;

// Adding ---

/**
 * @template TKey of int|string
 * @template T
 *
 * @param iterable<TKey, T> ...$iterables
 *
 * @return Generator<TKey, T, mixed, void>
 */
function iterable_merge(iterable ...$iterables): Generator
{
    foreach ($iterables as $iterable) {
        foreach ($iterable as $key => $value) {
            yield $key => $value;
        }
    }
}

/**
 * @template TKey of int|string
 * @template T
 *
 * @param iterable<TKey, T> $iterable
 * @param TKey              $key
 * @param T                 $value
 *
 * @return Generator<TKey, T, mixed, void>
 */
function iterable_set(iterable $iterable, mixed $key, mixed $value): Generator
{
    $found = false;

    foreach ($iterable as $existingKey => $existingValue) {
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
}

// Chunking ---

/**
 * @template TKey of int|string
 * @template T
 *
 * @param iterable<TKey, T> $iterable
 * @param positive-int      $size
 *
 * @return Generator<int, Generator<TKey, T, mixed, void>, mixed, void>
 */
function iterable_chunk(iterable $iterable, int $size): Generator
{
    $chunk = [];

    foreach (iterable_to_generator($iterable) as $key => $value) {
        $chunk[] = [$key, $value];

        if (count($chunk) === $size) {
            yield (static function () use ($chunk) {
                foreach ($chunk as [$key, $value]) {
                    yield $key => $value;
                }
            })();

            $chunk = [];
        }
    }

    if ([] !== $chunk) {
        yield (static function () use ($chunk) {
            foreach ($chunk as [$key, $value]) {
                yield $key => $value;
            }
        })();
    }
}

/**
 * @template T
 *
 * @param iterable<array-key, T> $iterable
 * @param positive-int           $size
 *
 * @return Generator<int, Generator<int, T, mixed, void>, mixed, void>
 */
function iterable_chunk_values(iterable $iterable, int $size): Generator
{
    $chunk = [];

    foreach (iterable_to_generator($iterable) as $value) {
        $chunk[] = $value;

        if (count($chunk) === $size) {
            yield (static function () use ($chunk) {
                yield from $chunk;
            })();

            $chunk = [];
        }
    }

    if ([] !== $chunk) {
        yield (static function () use ($chunk) {
            yield from $chunk;
        })();
    }
}

// Containing ---

/**
 * @template T
 *
 * @param iterable<array-key, T> $iterable
 * @param T                      $value
 */
function iterable_contains(iterable $iterable, mixed $value): bool
{
    foreach ($iterable as $item) {
        if ($item === $value) {
            return true;
        }
    }

    return false;
}

/**
 * @template TKey of int|string
 * @template T
 *
 * @param iterable<TKey, T> $iterable
 * @param TKey              $key
 */
function iterable_contains_key(iterable $iterable, int|string $key): bool
{
    if (is_array($iterable) && is_int($key)) {
        return array_key_exists($key, $iterable);
    }

    foreach ($iterable as $existingKey => $_) {
        if ($existingKey === $key) {
            return true;
        }
    }

    return false;
}

/**
 * @param iterable<mixed> $iterable
 *
 * @return ($iterable is non-empty-array ? false : ($iterable is array{} ? true : bool))
 */
function iterable_empty(iterable $iterable): bool
{
    foreach ($iterable as $_) {
        return false;
    }

    return true;
}

// Converting ---

/**
 * @template T of iterable<mixed>
 *
 * @param (Closure(): T)|T $value
 *
 * @return T
 */
function value(Closure|iterable $value): iterable
{
    return $value instanceof Closure ? $value() : $value;
}

/**
 * @template TKey of int|string
 * @template T
 *
 * @param iterable<TKey, T> $iterable
 *
 * @return array<TKey, T>
 */
function iterable_to_array(iterable $iterable): array
{
    $array = [];

    foreach ($iterable as $key => $value) {
        $array[$key] = $value;
    }

    return $array;
}

/**
 * @template TKey of int|string
 * @template T
 *
 * @param iterable<TKey, T> $iterable
 *
 * @return Generator<TKey, T, mixed, void>
 */
function iterable_to_generator(iterable $iterable): Generator
{
    foreach ($iterable as $key => $value) {
        yield $key => $value;
    }
}

// Filtering ---

/**
 * @template TKey of int|string
 * @template T
 *
 * @param iterable<TKey, T>         $iterable
 * @param (callable(T, TKey): bool) $callable
 *
 * @return Generator<TKey, T, mixed, void>
 */
function iterable_filter(iterable $iterable, callable $callable): Generator
{
    foreach ($iterable as $key => $value) {
        if (true === $callable($value, $key)) {
            yield $key => $value;
        }
    }
}

/**
 * @template TKey of int|string
 *
 * @param iterable<TKey, mixed> $iterable
 *
 * @return Generator<int, TKey, mixed, void>
 */
function iterable_search(iterable $iterable, mixed $value): Generator
{
    foreach ($iterable as $key => $item) {
        if ($item === $value) {
            yield $key;
        }
    }
}

// Mapping ---

/**
 * @template TKey of int|string
 * @template T
 * @template TReturn
 *
 * @param iterable<TKey, T>            $iterable
 * @param (callable(T, TKey): TReturn) $callable
 *
 * @return Generator<TKey, TReturn, mixed, void>
 */
function iterable_map(iterable $iterable, callable $callable): Generator
{
    foreach ($iterable as $key => $value) {
        yield $key => $callable($value, $key);
    }
}

// Removing ---

/**
 * @template TKey of int|string
 * @template T
 *
 * @param iterable<TKey, T> $iterable
 * @param non-negative-int  $offset
 * @param null|positive-int $length
 *
 * @return Generator<TKey, T, mixed, void>
 */
function iterable_slice(iterable $iterable, int $offset, ?int $length = null): Generator
{
    $index = 0;
    $extracted = 0;

    foreach ($iterable as $key => $value) {
        if ($index++ < $offset) {
            continue;
        }

        if (null !== $length && $extracted >= $length) {
            break;
        }

        yield $key => $value;
        ++$extracted;
    }
}

// Retrieving ---

/**
 * @template T
 *
 * @param iterable<T> $iterable
 *
 * @return Generator<int, T, mixed, void>
 */
function iterable_values(iterable $iterable): Generator
{
    foreach ($iterable as $value) {
        yield $value;
    }
}

/**
 * @template TKey of int|string
 *
 * @param iterable<TKey, mixed> $iterable
 *
 * @return Generator<int, TKey, mixed, void>
 */
function iterable_keys(iterable $iterable): Generator
{
    foreach ($iterable as $key => $_) {
        yield $key;
    }
}

/**
 * @param iterable<mixed> $iterable
 *
 * @return non-negative-int
 */
function iterable_count(iterable $iterable): int
{
    if (is_array($iterable)) {
        return count($iterable);
    }

    if ($iterable instanceof Countable) {
        return count($iterable);
    }

    return iterator_count($iterable);
}

/**
 * @template TKey of int|string
 * @template T
 *
 * @param iterable<TKey, T> $iterable
 * @param TKey              $key
 *
 * @return T
 *
 * @throws OffsetDoesntExistException
 */
function iterable_get(iterable $iterable, int|string $key): mixed
{
    if (is_array($iterable) && is_int($key) && array_key_exists($key, $iterable)) {
        return $iterable[$key];
    }

    foreach ($iterable as $k => $item) {
        if ($k === $key) {
            return $item;
        }
    }

    throw new OffsetDoesntExistException($key);
}

/**
 * @template T
 *
 * @param iterable<T> $iterable
 *
 * @return T
 *
 * @throws OffsetDoesntExistException
 */
function iterable_first(iterable $iterable): mixed
{
    foreach ($iterable as $item) {
        return $item;
    }

    throw new OffsetDoesntExistException('first');
}

/**
 * @template T
 *
 * @param iterable<T> $iterable
 *
 * @return T
 *
 * @throws OffsetDoesntExistException
 */
function iterable_last(iterable $iterable): mixed
{
    if (is_array($iterable) && [] !== $iterable) {
        return $iterable[array_key_last($iterable)];
    }

    $last = null;
    $found = false;

    foreach ($iterable as $item) {
        $last = $item;
        $found = true;
    }

    if (!$found) {
        throw new OffsetDoesntExistException('last');
    }

    return $last;
}
