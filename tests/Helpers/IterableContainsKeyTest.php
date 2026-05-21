<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_contains_key;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableContainsKeyTest extends TestCase
{
    public function testContainsKeyInAnArray(): void
    {
        $this->assertTrue(iterable_contains_key(['a' => 1, 'b' => 2], 'a'));
    }

    public function testDoesNotContainMissingKeyInArray(): void
    {
        $this->assertFalse(iterable_contains_key(['a' => 1, 'b' => 2], 'z'));
    }

    public function testContainsIntKeyInAnArray(): void
    {
        $this->assertTrue(iterable_contains_key([10, 20, 30], 1));
    }

    public function testContainsKeyInAnIterable(): void
    {
        $this->assertTrue(iterable_contains_key(new ArrayIterator(['a' => 1, 'b' => 2]), 'b'));
    }

    public function testContainsKeyInAGenerator(): void
    {
        $generator = (static function () {
            yield 'foo' => 1;

            yield 'bar' => 2;
        })();

        $this->assertTrue(iterable_contains_key($generator, 'foo'));
    }

    public function testUsesStrictKeyComparison(): void
    {
        $this->assertFalse(iterable_contains_key([1, 2, 3], '0'));
    }

    public function testReturnsFalseForEmptyIterable(): void
    {
        $this->assertFalse(iterable_contains_key([], 'a'));
    }
}
