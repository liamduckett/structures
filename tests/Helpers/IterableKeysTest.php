<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_keys;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableKeysTest extends TestCase
{
    public function testKeysOfAnIntKeyedArray(): void
    {
        $result = iterable_keys([10, 20, 30]);

        $this->assertSame([0, 1, 2], iterator_to_array($result));
    }

    public function testKeysOfAStringKeyedArray(): void
    {
        $result = iterable_keys(['a' => 1, 'b' => 2, 'c' => 3]);

        $this->assertSame(['a', 'b', 'c'], iterator_to_array($result));
    }

    public function testKeysDiscardsValues(): void
    {
        $result = iterable_keys(['x' => 100, 'y' => 200]);

        $this->assertSame(['x', 'y'], iterator_to_array($result));
    }

    public function testKeysOfAnIterable(): void
    {
        $result = iterable_keys(new ArrayIterator(['a' => 1, 'b' => 2]));

        $this->assertSame(['a', 'b'], iterator_to_array($result));
    }

    public function testKeysOfAGenerator(): void
    {
        $generator = (static function () {
            yield 'foo' => 1;

            yield 'bar' => 2;
        })();

        $result = iterable_keys($generator);

        $this->assertSame(['foo', 'bar'], iterator_to_array($result));
    }

    public function testKeysOfEmptyIterable(): void
    {
        $result = iterable_keys([]);

        $this->assertSame([], iterator_to_array($result));
    }
}
