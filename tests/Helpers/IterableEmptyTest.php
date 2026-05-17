<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_empty;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableEmptyTest extends TestCase
{
    public function testEmptyReturnsTrueForEmptyArray(): void
    {
        $this->assertTrue(iterable_empty([]));
    }

    public function testEmptyReturnsFalseForNonEmptyArray(): void
    {
        $this->assertFalse(iterable_empty([1, 2, 3]));
    }

    public function testEmptyReturnsTrueForEmptyIterable(): void
    {
        $this->assertTrue(iterable_empty(new ArrayIterator([])));
    }

    public function testEmptyReturnsFalseForNonEmptyIterable(): void
    {
        $this->assertFalse(iterable_empty(new ArrayIterator([1])));
    }

    public function testEmptyReturnsFalseForNonEmptyGenerator(): void
    {
        $generator = (function () {
            yield 1;
        })();

        $this->assertFalse(iterable_empty($generator));
    }
}
