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
     * @param Dictionary<T>              $dictionary
     * @param array<non-empty-string, T> $expected
     */
    protected function assertDictionary(Dictionary $dictionary, array $expected): void
    {
        $this->assertSame($expected, $dictionary->array());
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
     * @template T of mixed
     *
     * @param Sequence<Dictionary<T>>          $sequence
     * @param list<array<non-empty-string, T>> $expected
     */
    protected function assertSequenceOfDictionaries(Sequence $sequence, array $expected): void
    {
        $actual = [];

        foreach ($sequence as $dictionary) {
            $actual[] = $dictionary->array();
        }

        $this->assertSame($expected, $actual);
    }

    /**
     * @template T of mixed
     *
     * @param Sequence<Sequence<T>> $sequence
     * @param list<list<T>>         $expected
     */
    protected function assertSequenceOfSequences(Sequence $sequence, array $expected): void
    {
        $actual = [];

        foreach ($sequence as $innerSequence) {
            $actual[] = $innerSequence->array();
        }

        $this->assertSame($expected, $actual);
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
     * @template TKey of array-key
     * @template T of mixed
     *
     * @param iterable<TKey, T> $iterable
     * @param array<TKey, T>    $expected
     */
    protected function assertIteratesTwice(iterable $iterable, array $expected): void
    {
        $first = [];

        foreach ($iterable as $key => $value) {
            $first[$key] = $value;
        }

        $second = [];

        foreach ($iterable as $key => $value) {
            $second[$key] = $value;
        }

        $this->assertSame($expected, $first);
        $this->assertSame($expected, $second);
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
