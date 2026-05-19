<?php

namespace Liamduckett\Structures;

use Closure;
use Generator;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;

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
 * @template TKey of array-key
 * @template T
 *
 * @param iterable<TKey, T> $iterable
 *
 * @return array<TKey, T>
 */
function iterable_to_array(iterable $iterable): array
{
    if (is_array($iterable)) {
        return $iterable;
    }

    return iterator_to_array($iterable);
}

/**
 * @template TKey of array-key
 * @template T
 * @template TReturn
 *
 * @param iterable<TKey, T>            $iterable
 * @param (callable(T, TKey): TReturn) $callable
 *
 * @return Generator<TKey, TReturn, null, void>
 */
function iterable_map(iterable $iterable, callable $callable): Generator
{
    foreach ($iterable as $key => $value) {
        yield $key => $callable($value, $key);
    }
}

/**
 * @template TKey of array-key
 * @template T
 *
 * @param iterable<TKey, T>         $iterable
 * @param (callable(T, TKey): bool) $callable
 *
 * @return Generator<TKey, T, null, void>
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
 * @template T
 *
 * @param iterable<T> $iterable
 *
 * @return Generator<int, T, null, void>
 */
function iterable_values(iterable $iterable): Generator
{
    foreach ($iterable as $value) {
        yield $value;
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

    return iterator_count($iterable);
}

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
 * @param iterable<mixed> $iterable
 */
function iterable_empty(iterable $iterable): bool
{
    foreach ($iterable as $_) {
        return false;
    }

    return true;
}

/**
 * @template TKey of array-key
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

/**
 * @template TKey of array-key
 * @template T
 *
 * @param iterable<TKey, T> ...$iterables
 *
 * @return Generator<TKey, T, null, void>
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
 * @template TKey of array-key
 * @template T
 *
 * @param iterable<TKey, T> $iterable
 *
 * @return Generator<TKey, T, mixed, void>
 */
function generator(iterable $iterable): Generator
{
    foreach ($iterable as $key => $value) {
        yield $key => $value;
    }
}

/**
 * @template TKey of array-key
 * @template T
 *
 * @param iterable<TKey, T> $iterable
 * @param positive-int      $size
 *
 * @return Generator<int, Generator<TKey, T, mixed, void>, mixed, void>
 */
function iterable_chunk(iterable $iterable, int $size): Generator
{
    $generator = generator($iterable);

    $remaining = 0;

    while ($generator->valid()) {
        while ($remaining > 0 && $generator->valid()) {
            $generator->next();
            --$remaining;
        }

        if (!$generator->valid()) {
            break;
        }

        $remaining = $size;

        yield (static function () use ($generator, &$remaining) {
            while ($generator->valid() && $remaining > 0) {
                $key = $generator->key();
                $value = $generator->current();

                --$remaining;
                $generator->next();

                yield $key => $value;
            }
        })();
    }
}
