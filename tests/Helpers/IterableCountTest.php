<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_count;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableCountTest extends TestCase
{
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
        $generator = (function () {
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
}
