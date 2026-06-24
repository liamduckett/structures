<?php

namespace Helpers;

use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_chunk_values;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableChunkValuesTest extends TestCase
{
    public function testExhaustiveYielding(): void
    {
        $chunks = iterable_chunk_values([1, 2, 3, 4, 5], 2);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([
            [
                0 => 1,
                1 => 2,
            ], [
                0 => 3,
                1 => 4,
            ], [
                0 => 5,
            ],
        ], $result);
    }

    public function testPartialYielding(): void
    {
        $chunks = iterable_chunk_values([1, 2, 3, 4, 5], 2);

        $result = [];

        foreach ($chunks as $chunk) {
            foreach ($chunk as $item) {
                $result[] = $item;

                break;
            }
        }

        $this->assertSame([1, 3, 5], $result);
    }

    public function testNoYielding(): void
    {
        $chunks = iterable_chunk_values([1, 2, 3, 4, 5], 2);

        $count = 0;

        foreach ($chunks as $_) {
            ++$count;
        }

        $this->assertSame(3, $count);
    }

    public function testChunkingAGenerator(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;

            yield 3;

            yield 4;

            yield 5;
        })();

        $chunks = iterable_chunk_values($generator, 2);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([
            [
                0 => 1,
                1 => 2,
            ], [
                0 => 3,
                1 => 4,
            ], [
                0 => 5,
            ],
        ], $result);
    }

    public function testChunkWithSizeEqualToLength(): void
    {
        $chunks = iterable_chunk_values([1, 2, 3], 3);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2, 2 => 3]], $result);
    }

    public function testChunkWithSizeGreaterThanLength(): void
    {
        $chunks = iterable_chunk_values([1, 2], 10);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2]], $result);
    }

    public function testKeysAreNotPreserved(): void
    {
        $chunks = iterable_chunk_values(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], 2);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([
            [
                0 => 1,
                1 => 2,
            ], [
                0 => 3,
                1 => 4,
            ], [
                0 => 5,
            ],
        ], $result);
    }
}
