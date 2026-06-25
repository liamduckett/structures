<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_contains;
use function Liamduckett\Structures\iterable_contains_key;
use function Liamduckett\Structures\iterable_empty;

/**
 * @internal
 *
 * @coversNothing
 */
class ContainingTest extends TestCase
{
    // Contains ---

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

    // Contains Key ---

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

    public function testContainsKeyUsesStrictKeyComparison(): void
    {
        $this->assertFalse(iterable_contains_key([1, 2, 3], '0'));
    }

    public function testContainsKeyReturnsFalseForEmptyIterable(): void
    {
        $this->assertFalse(iterable_contains_key([], 'a'));
    }

    // Empty ---

    public function testEmptyReturnsTrueForEmptyArray(): void
    {
        $this->assertTrue(iterable_empty([])); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testEmptyReturnsFalseForNonEmptyArray(): void
    {
        $this->assertFalse(iterable_empty([1, 2, 3])); // @phpstan-ignore method.alreadyNarrowedType
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
        $generator = (static function () {
            yield 1;
        })();

        $this->assertFalse(iterable_empty($generator));
    }

    public function testEmptyReturnsTrueForEmptyGenerator(): void
    {
        $generator = (static function () {
            yield from [];
        })();

        $this->assertTrue(iterable_empty($generator));
    }
}
