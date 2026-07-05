<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_filter;
use function Liamduckett\Structures\iterable_search;

/**
 * @internal
 *
 * @coversNothing
 */
class FilteringTest extends TestCase
{
    // Filter ---

    public function testFilterAnArray(): void
    {
        $result = iterable_filter([1, 2, 3, 4], static fn (int $value) => $value > 2);

        $this->assertSame([2 => 3, 3 => 4], iterator_to_array($result));
    }

    public function testFilterAnIterable(): void
    {
        $result = iterable_filter(new ArrayIterator([1, 2, 3, 4]), static fn (int $value) => $value > 2);

        $this->assertSame([2 => 3, 3 => 4], iterator_to_array($result));
    }

    public function testFilterAGenerator(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;

            yield 3;
        })();

        $result = iterable_filter($generator, static fn (int $value) => $value > 1);

        $this->assertSame([1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testFilterPassesKeyToCallable(): void
    {
        $result = iterable_filter(['a' => 1, 'b' => 2, 'c' => 3], static fn (int $value, string $key) => 'b' !== $key);

        $this->assertSame(['a' => 1, 'c' => 3], iterator_to_array($result));
    }

    public function testFilterPreservesKeys(): void
    {
        $result = iterable_filter([1, 2, 3], static fn (int $value) => $value > 1);

        $this->assertSame([1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testFilterReturnsEmptyWhenNothingMatches(): void
    {
        $result = iterable_filter(new ArrayIterator([1, 2, 3]), static fn (int $value) => $value > 99);

        $this->assertSame([], iterator_to_array($result));
    }

    public function testFilterIsLazy(): void
    {
        $calls = 0;

        iterable_filter([1, 2, 3], static function (int $value) use (&$calls): bool {
            ++$calls;

            return $value > 1;
        });

        $this->assertSame(0, $calls);
    }

    // Search ---

    public function testSearchReturnsKeysOfMatchingValues(): void
    {
        $result = iterable_search(['foo' => 1, 'bar' => 2, 'baz' => 1], 1);

        $this->assertSame(['foo', 'baz'], iterator_to_array($result));
    }

    public function testSearchReturnsIntegerKeysForSequentialIterables(): void
    {
        $result = iterable_search([10, 20, 30, 20], 20);

        $this->assertSame([1, 3], iterator_to_array($result));
    }

    public function testSearchReturnsEmptyWhenNothingMatches(): void
    {
        $result = iterable_search([1, 2, 3], 99);

        $this->assertSame([], iterator_to_array($result));
    }

    public function testSearchUsesStrictComparison(): void
    {
        $result = iterable_search(['foo' => 1, 'bar' => '1'], 1);

        $this->assertSame(['foo'], iterator_to_array($result));
    }
}
