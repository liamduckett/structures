<?php

namespace Liamduckett\Structures;

use Closure;
use Generator;

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
 * @template T of mixed
 * @template TReturn of mixed
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
 * @template T of mixed
 *
 * @param iterable<TKey, T>         $iterable
 * @param (callable(T, TKey): bool) $callable
 *
 * @return Generator<TKey, T, null, void>
 */
function iterable_filter(iterable $iterable, callable $callable): Generator
{
    foreach ($iterable as $key => $value) {
        if ($callable($value, $key)) {
            yield $key => $value;
        }
    }
}
