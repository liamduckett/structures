<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_to_array;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableToArrayTest extends TestCase
{
    public function testConvertsAnArray(): void
    {
        $result = iterable_to_array([1, 2, 3]);

        $this->assertSame([1, 2, 3], $result);
    }

    public function testConvertsAnIterable(): void
    {
        $result = iterable_to_array(new ArrayIterator([1, 2, 3]));

        $this->assertSame([1, 2, 3], $result);
    }

    public function testConvertsAGenerator(): void
    {
        $result = iterable_to_array((static function () {
            yield 'a' => 1;

            yield 'b' => 2;
        })());

        $this->assertSame(['a' => 1, 'b' => 2], $result);
    }

    public function testPreservesKeys(): void
    {
        $result = iterable_to_array(['a' => 1, 'b' => 2]);

        $this->assertSame(['a' => 1, 'b' => 2], $result);
    }

    public function testConvertsAnEmptyArray(): void
    {
        $result = iterable_to_array([]);

        $this->assertSame([], $result); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testConvertsAnEmptyGenerator(): void
    {
        $result = iterable_to_array((static function () {
            yield from [];
        })());

        $this->assertSame([], $result); // @phpstan-ignore method.alreadyNarrowedType
    }
}
