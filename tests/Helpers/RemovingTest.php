<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_slice;

/**
 * @internal
 *
 * @coversNothing
 */
class RemovingTest extends TestCase
{
    // Slice ---

    public function testSliceExtractsItemsAtOffset(): void
    {
        $result = iterable_slice([1, 2, 3, 4, 5], 1, 3);

        $this->assertSame([1 => 2, 2 => 3, 3 => 4], iterator_to_array($result));
    }

    public function testSliceWithNullLengthGoesToEnd(): void
    {
        $result = iterable_slice([1, 2, 3, 4, 5], 2);

        $this->assertSame([2 => 3, 3 => 4, 4 => 5], iterator_to_array($result));
    }

    public function testSliceASingleItem(): void
    {
        $result = iterable_slice([1, 2, 3], 1, 1);

        $this->assertSame([1 => 2], iterator_to_array($result));
    }

    public function testSlicePreservesKeys(): void
    {
        $result = iterable_slice(['a' => 1, 'b' => 2, 'c' => 3], 1, 2);

        $this->assertSame(['b' => 2, 'c' => 3], iterator_to_array($result));
    }

    public function testSliceAnIterable(): void
    {
        $result = iterable_slice(new ArrayIterator([1, 2, 3, 4]), 1, 2);

        $this->assertSame([1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testSliceAGenerator(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;

            yield 3;

            yield 4;
        })();

        $result = iterable_slice($generator, 2);

        $this->assertSame([2 => 3, 3 => 4], iterator_to_array($result));
    }

    public function testSliceWithOffsetBeyondLengthReturnsEmpty(): void
    {
        $result = iterable_slice([1, 2, 3], 10);

        $this->assertSame([], iterator_to_array($result));
    }

    public function testSliceWithLengthGreaterThanRemaining(): void
    {
        $result = iterable_slice([1, 2, 3], 1, 10);

        $this->assertSame([1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testSliceOfEmptyIterableReturnsEmpty(): void
    {
        $result = iterable_slice([], 0);

        $this->assertSame([], iterator_to_array($result));
    }
}
