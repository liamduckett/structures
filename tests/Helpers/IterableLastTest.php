<?php

namespace Tests\Helpers;

use ArrayIterator;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_last;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableLastTest extends TestCase
{
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
