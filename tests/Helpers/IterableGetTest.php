<?php

namespace Tests\Helpers;

use ArrayIterator;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_get;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableGetTest extends TestCase
{
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
}
