<?php

namespace Tests\Helpers;

use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_chunk;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableChunkTest extends TestCase
{
    public function testExhaustiveYielding(): void
    {
        $chunks = iterable_chunk([1, 2, 3, 4, 5, 6], 3);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2, 2 => 3], [3 => 4, 4 => 5, 5 => 6]], $result);
    }

    public function testPartialYielding(): void
    {
        $chunks = iterable_chunk([1, 2, 3, 4, 5, 6], 3);

        $result = [];

        foreach ($chunks as $chunk) {
            foreach ($chunk as $item) {
                $result[] = $item;

                break;
            }
        }

        $this->assertSame([1, 4], $result);
    }

    public function testNoYielding(): void
    {
        $chunks = iterable_chunk([1, 2, 3, 4, 5, 6], 3);

        $count = 0;

        foreach ($chunks as $chunk) {
            ++$count;
        }

        $this->assertSame(2, $count);
    }

    public function testChunkingAGenerator(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;

            yield 3;

            yield 4;

            yield 5;

            yield 6;
        })();

        $chunks = iterable_chunk($generator, 3);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2, 2 => 3], [3 => 4, 4 => 5, 5 => 6]], $result);
    }

    public function testKeysArePreserved(): void
    {
        $chunks = iterable_chunk(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4], 2);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([['a' => 1, 'b' => 2], ['c' => 3, 'd' => 4]], $result);
    }
}
