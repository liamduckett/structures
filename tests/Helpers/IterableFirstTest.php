<?php

namespace Tests\Helpers;

use ArrayIterator;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_first;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableFirstTest extends TestCase
{
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
        $generator = (function () {
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
}
