<?php

namespace Tests\Concerns;

use ArrayIterator;
use Liamduckett\Structures\Dictionary;
use Liamduckett\Structures\Sequence;
use Traversable;

trait TestsStructures
{
    /**
     * @template T of mixed
     *
     * @param Dictionary<T>              $dict
     * @param array<non-empty-string, T> $expected
     */
    protected function assertDictionary(Dictionary $dict, array $expected): void
    {
        $this->assertSame($expected, $dict->array());
    }

    /**
     * @template T of mixed
     *
     * @param Sequence<T> $sequence
     * @param list<T>     $expected
     */
    protected function assertSequence(Sequence $sequence, array $expected): void
    {
        $this->assertSame($expected, $sequence->array());
    }

    /**
     * @template TKey of array-key
     * @template T of mixed
     *
     * @param Traversable<TKey, T> $iterator
     * @param array<TKey, T>       $expected
     */
    protected function assertIterator(Traversable $iterator, array $expected): void
    {
        $this->assertSame($expected, iterator_to_array($iterator));
    }

    /**
     *  Used to work around a static analysis limitation.
     *
     * @see https://github.com/phpstan/phpstan/issues/10289
     *
     * @template TKey of int|string
     * @template T of mixed
     *
     * @param array<TKey, T> $items
     *
     * @return ArrayIterator<TKey, T>
     */
    protected function buildTypedIterator(array $items): ArrayIterator
    {
        return new ArrayIterator($items);
    }
}
