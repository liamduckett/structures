<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_contains;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableContainsTest extends TestCase
{
    public function testContainsAnItemInAnArray(): void
    {
        $this->assertTrue(iterable_contains([1, 2, 3], 2));
    }

    public function testDoesNotContainMissingItemInArray(): void
    {
        $this->assertFalse(iterable_contains([1, 2, 3], 99));
    }

    public function testContainsAnItemInAnIterable(): void
    {
        $this->assertTrue(iterable_contains(new ArrayIterator([1, 2, 3]), 2));
    }

    public function testContainsAnItemInAGenerator(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;

            yield 3;
        })();

        $this->assertTrue(iterable_contains($generator, 2));
    }

    public function testContainsUsesStrictEquality(): void
    {
        $this->assertFalse(iterable_contains([1, 2, 3], '1'));
        $this->assertFalse(iterable_contains([1, 2, 3], true));
    }

    public function testContainsReturnsFalseForEmptyIterable(): void
    {
        $this->assertFalse(iterable_contains([], 1));
    }
}
