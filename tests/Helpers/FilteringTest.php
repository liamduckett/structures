<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_filter;

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
}
