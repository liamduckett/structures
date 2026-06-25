<?php

namespace Tests\Helpers;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;
use PHPUnit\Framework\TestCase;
use Traversable;

use function Liamduckett\Structures\iterable_count;
use function Liamduckett\Structures\iterable_first;
use function Liamduckett\Structures\iterable_get;
use function Liamduckett\Structures\iterable_keys;
use function Liamduckett\Structures\iterable_last;
use function Liamduckett\Structures\iterable_values;

/**
 * @internal
 *
 * @coversNothing
 */
class RetrievingTest extends TestCase
{
    // Values ---

    public function testValuesOfAnArray(): void
    {
        $result = iterable_values([10, 20, 30]);

        $this->assertSame([10, 20, 30], iterator_to_array($result));
    }

    public function testValuesDiscardsKeys(): void
    {
        $result = iterable_values(['a' => 1, 'b' => 2, 'c' => 3]);

        $this->assertSame([1, 2, 3], iterator_to_array($result));
    }

    public function testValuesOfAnIterable(): void
    {
        $result = iterable_values(new ArrayIterator([1, 2, 3]));

        $this->assertSame([1, 2, 3], iterator_to_array($result));
    }

    public function testValuesOfAGenerator(): void
    {
        $generator = (static function () {
            yield 'x' => 1;

            yield 'y' => 2;

            yield 'z' => 3;
        })();

        $result = iterable_values($generator);

        $this->assertSame([1, 2, 3], iterator_to_array($result));
    }

    public function testValuesReturnsEmptyForEmptyIterable(): void
    {
        $result = iterable_values([]);

        $this->assertSame([], iterator_to_array($result));
    }

    // Keys ---

    public function testKeysOfAnIntKeyedArray(): void
    {
        $result = iterable_keys([10, 20, 30]);

        $this->assertSame([0, 1, 2], iterator_to_array($result));
    }

    public function testKeysOfAStringKeyedArray(): void
    {
        $result = iterable_keys(['a' => 1, 'b' => 2, 'c' => 3]);

        $this->assertSame(['a', 'b', 'c'], iterator_to_array($result));
    }

    public function testKeysDiscardsValues(): void
    {
        $result = iterable_keys(['x' => 100, 'y' => 200]);

        $this->assertSame(['x', 'y'], iterator_to_array($result));
    }

    public function testKeysOfAnIterable(): void
    {
        $result = iterable_keys(new ArrayIterator(['a' => 1, 'b' => 2]));

        $this->assertSame(['a', 'b'], iterator_to_array($result));
    }

    public function testKeysOfAGenerator(): void
    {
        $generator = (static function () {
            yield 'foo' => 1;

            yield 'bar' => 2;
        })();

        $result = iterable_keys($generator);

        $this->assertSame(['foo', 'bar'], iterator_to_array($result));
    }

    public function testKeysOfEmptyIterable(): void
    {
        $result = iterable_keys([]);

        $this->assertSame([], iterator_to_array($result));
    }

    // Count ---

    public function testCountOfAnArray(): void
    {
        $this->assertSame(3, iterable_count([1, 2, 3]));
    }

    public function testCountOfAnEmptyArray(): void
    {
        $this->assertSame(0, iterable_count([]));
    }

    public function testCountOfAnIterable(): void
    {
        $this->assertSame(3, iterable_count(new ArrayIterator([1, 2, 3])));
    }

    public function testCountOfAGenerator(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;

            yield 3;
        })();

        $this->assertSame(3, iterable_count($generator));
    }

    public function testCountOfEmptyIterable(): void
    {
        $this->assertSame(0, iterable_count(new ArrayIterator([])));
    }

    public function testCountUsesCountableInterfaceRatherThanIterating(): void
    {
        $countable = new class implements Countable, IteratorAggregate {
            public function count(): int
            {
                return 99;
            }

            public function getIterator(): Traversable
            {
                return new ArrayIterator([1, 2, 3]);
            }
        };

        $this->assertSame(99, iterable_count($countable));
    }

    // Get ---

    public function testGetReturnsItemAtIndexFromArray(): void
    {
        $this->assertSame(20, iterable_get([10, 20, 30], 1));
    }

    public function testGetReturnsFirstItem(): void
    {
        $this->assertSame(10, iterable_get([10, 20, 30], 0));
    }

    public function testGetReturnsLastItem(): void
    {
        $this->assertSame(30, iterable_get([10, 20, 30], 2));
    }

    public function testGetFromIterable(): void
    {
        $this->assertSame(20, iterable_get(new ArrayIterator([10, 20, 30]), 1));
    }

    public function testGetFromGenerator(): void
    {
        $generator = (static function () {
            yield 10;

            yield 20;

            yield 30;
        })();

        $this->assertSame(20, iterable_get($generator, 1));
    }

    public function testGetThrowsForOutOfBoundsIndex(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        iterable_get([1, 2, 3], 99);
    }

    public function testGetThrowsForEmptyIterable(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        iterable_get([], 0);
    }

    public function testGetReturnsItemByStringKey(): void
    {
        $this->assertSame(2, iterable_get(['a' => 1, 'b' => 2], 'b'));
    }

    public function testGetReturnsFirstItemByStringKey(): void
    {
        $this->assertSame(1, iterable_get(['a' => 1, 'b' => 2], 'a'));
    }

    public function testGetThrowsForMissingStringKey(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        iterable_get(['a' => 1, 'b' => 2], 'z');
    }

    public function testGetUsesStrictKeyComparison(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        iterable_get([10, 20, 30], '1');
    }

    // First ---

    public function testFirstReturnsFirstItemFromArray(): void
    {
        $this->assertSame(10, iterable_first([10, 20, 30]));
    }

    public function testFirstReturnsSingleItemArray(): void
    {
        /** @var list<int> $items */
        $items = [42];
        $this->assertSame(42, iterable_first($items));
    }

    public function testFirstFromIterable(): void
    {
        $this->assertSame(10, iterable_first(new ArrayIterator([10, 20, 30])));
    }

    public function testFirstFromGenerator(): void
    {
        $generator = (static function () {
            yield 10;

            yield 20;

            yield 30;
        })();

        $this->assertSame(10, iterable_first($generator));
    }

    public function testFirstThrowsForEmptyArray(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        iterable_first([]);
    }

    public function testFirstThrowsForEmptyIterable(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        iterable_first(new ArrayIterator([]));
    }

    // Last ---

    public function testLastReturnsLastItemFromArray(): void
    {
        $this->assertSame(30, iterable_last([10, 20, 30]));
    }

    public function testLastReturnsSingleItemArray(): void
    {
        /** @var list<int> $items */
        $items = [42];
        $this->assertSame(42, iterable_last($items));
    }

    public function testLastFromIterable(): void
    {
        $this->assertSame(30, iterable_last(new ArrayIterator([10, 20, 30])));
    }

    public function testLastFromGenerator(): void
    {
        $generator = (static function () {
            yield 10;

            yield 20;

            yield 30;
        })();

        $this->assertSame(30, iterable_last($generator));
    }

    public function testLastWorksWithNullValues(): void
    {
        $this->assertNull(iterable_last([1, 2, null]));
    }

    public function testLastThrowsForEmptyArray(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        iterable_last([]);
    }

    public function testLastThrowsForEmptyIterable(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        iterable_last(new ArrayIterator([]));
    }
}
