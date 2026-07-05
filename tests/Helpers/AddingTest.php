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

    public function testMergeTwoArrays(): void
    {
        $result = iterable_merge([1, 2], [3, 4]);

        $this->assertSame([1, 2, 3, 4], iterator_to_array($result, false));
    }

    public function testMergeThreeArrays(): void
    {
        $result = iterable_merge([1], [2], [3]);

        $this->assertSame([1, 2, 3], iterator_to_array($result, false));
    }

    public function testMergeWithIterable(): void
    {
        $result = iterable_merge([1, 2], new ArrayIterator([3, 4]));

        $this->assertSame([1, 2, 3, 4], iterator_to_array($result, false));
    }

    public function testMergeWithGenerator(): void
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

    public function testMergeWithSingleIterable(): void
    {
        $result = iterable_merge([1, 2, 3]);

        $this->assertSame([1, 2, 3], iterator_to_array($result, false));
    }

    public function testMergeWithEmptyIterables(): void
    {
        $result = iterable_merge([], []);

        $this->assertSame([], iterator_to_array($result));
    }

    // Set ---

    public function testSetAppendsANewKey(): void
    {
        $result = iterable_set(['foo' => 1], 'bar', 2);

        $this->assertSame(['foo' => 1, 'bar' => 2], iterator_to_array($result));
    }

    public function testSetUpdatesAnExistingKeyInPlace(): void
    {
        $result = iterable_set(['foo' => 1, 'bar' => 2], 'foo', 99);

        $this->assertSame(['foo' => 99, 'bar' => 2], iterator_to_array($result));
    }

    public function testSetAppendsToAnEmptyIterable(): void
    {
        $result = iterable_set([], 'foo', 1);

        $this->assertSame(['foo' => 1], iterator_to_array($result));
    }

    public function testSetWorksWithAnIterable(): void
    {
        $result = iterable_set(new ArrayIterator(['foo' => 1]), 'bar', 2);

        $this->assertSame(['foo' => 1, 'bar' => 2], iterator_to_array($result));
    }
}
