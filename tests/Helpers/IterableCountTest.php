<?php

namespace Tests\Helpers;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;
use Traversable;

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
}
