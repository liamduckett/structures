<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_merge;
use function Liamduckett\Structures\iterable_set;

/**
 * @internal
 *
 * @coversNothing
 */
class AddingTest extends TestCase
{
    // Merge ---

    public function testMergeCombinesTwoArrays(): void
    {
        $result = iterable_merge([1, 2], [3, 4]);

        $this->assertSame([1, 2, 3, 4], iterator_to_array($result, false));
    }

    public function testMergeCombinesThreeArrays(): void
    {
        $result = iterable_merge([1], [2], [3]);

        $this->assertSame([1, 2, 3], iterator_to_array($result, false));
    }

    public function testMergeAcceptsIterables(): void
    {
        $result = iterable_merge([1, 2], new ArrayIterator([3, 4]));

        $this->assertSame([1, 2, 3, 4], iterator_to_array($result, false));
    }

    public function testMergeAcceptsGenerators(): void
    {
        $generator = (static function () {
            yield 3;

            yield 4;
        })();

        $result = iterable_merge([1, 2], $generator);

        $this->assertSame([1, 2, 3, 4], iterator_to_array($result, false));
    }

    public function testMergePreservesStringKeys(): void
    {
        $result = iterable_merge(['a' => 1], ['b' => 2]);

        $this->assertSame(['a' => 1, 'b' => 2], iterator_to_array($result));
    }

    public function testMergeCombinesSingleIterable(): void
    {
        $result = iterable_merge([1, 2, 3]);

        $this->assertSame([1, 2, 3], iterator_to_array($result, false));
    }

    public function testMergeReturnsEmptyForEmptyIterables(): void
    {
        $result = iterable_merge([], []);

        $this->assertSame([], iterator_to_array($result));
    }

    // Set ---

    public function testSetAppendsNewKey(): void
    {
        $result = iterable_set(['foo' => 1], 'bar', 2);

        $this->assertSame(['foo' => 1, 'bar' => 2], iterator_to_array($result));
    }

    public function testSetUpdatesExistingKeyInPlace(): void
    {
        $result = iterable_set(['foo' => 1, 'bar' => 2], 'foo', 99);

        $this->assertSame(['foo' => 99, 'bar' => 2], iterator_to_array($result));
    }

    public function testSetAppendsToEmptyIterable(): void
    {
        $result = iterable_set([], 'foo', 1);

        $this->assertSame(['foo' => 1], iterator_to_array($result));
    }

    public function testSetAcceptsIterables(): void
    {
        $result = iterable_set(new ArrayIterator(['foo' => 1]), 'bar', 2);

        $this->assertSame(['foo' => 1, 'bar' => 2], iterator_to_array($result));
    }
}
