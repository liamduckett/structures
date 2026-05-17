<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_values;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableValuesTest extends TestCase
{
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
        $generator = (function () {
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
}
