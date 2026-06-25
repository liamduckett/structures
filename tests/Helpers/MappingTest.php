<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_map;

/**
 * @internal
 *
 * @coversNothing
 */
class MappingTest extends TestCase
{
    // Map ---

    public function testMapOverAnArray(): void
    {
        $result = iterable_map([1, 2, 3], static fn (int $value) => $value * 2);

        $this->assertSame([0 => 2, 1 => 4, 2 => 6], iterator_to_array($result));
    }

    public function testMapOverAnIterable(): void
    {
        $result = iterable_map(new ArrayIterator([1, 2, 3]), static fn (int $value) => $value * 2);

        $this->assertSame([0 => 2, 1 => 4, 2 => 6], iterator_to_array($result));
    }

    public function testMapOverAGenerator(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;
        })();

        $result = iterable_map($generator, static fn (int $value) => $value * 2);

        $this->assertSame([0 => 2, 1 => 4], iterator_to_array($result));
    }

    public function testMapPassesKeyToCallable(): void
    {
        $result = iterable_map(['a' => 1, 'b' => 2], static fn (int $value, string $key) => $key.$value);

        $this->assertSame(['a' => 'a1', 'b' => 'b2'], iterator_to_array($result));
    }

    public function testMapPreservesKeys(): void
    {
        $result = iterable_map(['a' => 1, 'b' => 2], static fn (int $value) => $value * 2);

        $this->assertSame(['a' => 2, 'b' => 4], iterator_to_array($result));
    }

    public function testMapIsLazy(): void
    {
        $calls = 0;

        iterable_map([1, 2, 3], static function (int $value) use (&$calls): int {
            ++$calls;

            return $value;
        });

        $this->assertSame(0, $calls);
    }
}
