<?php

namespace Tests\Helpers;

use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_chunk;
use function Liamduckett\Structures\iterable_chunk_values;

/**
 * @internal
 *
 * @coversNothing
 */
class ChunkingTest extends TestCase
{
    // Chunk ---

    public function testChunkExhaustiveYielding(): void
    {
        $chunks = iterable_chunk([1, 2, 3, 4, 5], 2);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([
            [
                0 => 1,
                1 => 2,
            ], [
                2 => 3,
                3 => 4,
            ], [
                4 => 5,
            ],
        ], $result);
    }

    public function testChunkPartialYielding(): void
    {
        $chunks = iterable_chunk([1, 2, 3, 4, 5], 2);

        $result = [];

        foreach ($chunks as $chunk) {
            foreach ($chunk as $item) {
                $result[] = $item;

                break;
            }
        }

        $this->assertSame([1, 3, 5], $result);
    }

    public function testChunkNoYielding(): void
    {
        $chunks = iterable_chunk([1, 2, 3, 4, 5], 2);

        $count = 0;

        foreach ($chunks as $_) {
            ++$count;
        }

        $this->assertSame(3, $count);
    }

    public function testChunkAGenerator(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;

            yield 3;

            yield 4;

            yield 5;
        })();

        $chunks = iterable_chunk($generator, 2);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([
            [
                0 => 1,
                1 => 2,
            ], [
                2 => 3,
                3 => 4,
            ], [
                4 => 5,
            ],
        ], $result);
    }

    public function testChunkWithSizeEqualToLength(): void
    {
        $chunks = iterable_chunk([1, 2, 3], 3);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2, 2 => 3]], $result);
    }

    public function testChunkWithSizeGreaterThanLength(): void
    {
        $chunks = iterable_chunk([1, 2], 10);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2]], $result);
    }

    public function testChunkKeysArePreserved(): void
    {
        $chunks = iterable_chunk(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], 2);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([
            ['a' => 1, 'b' => 2],
            ['c' => 3, 'd' => 4],
            ['e' => 5],
        ], $result);
    }

    // Chunk Values ---

    public function testChunkValuesExhaustiveYielding(): void
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

    public function testChunkValuesPartialYielding(): void
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

    public function testChunkValuesNoYielding(): void
    {
        $chunks = iterable_chunk_values([1, 2, 3, 4, 5], 2);

        $count = 0;

        foreach ($chunks as $_) {
            ++$count;
        }

        $this->assertSame(3, $count);
    }

    public function testChunkValuesAGenerator(): void
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

    public function testChunkValuesWithSizeEqualToLength(): void
    {
        $chunks = iterable_chunk_values([1, 2, 3], 3);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2, 2 => 3]], $result);
    }

    public function testChunkValuesWithSizeGreaterThanLength(): void
    {
        $chunks = iterable_chunk_values([1, 2], 10);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2]], $result);
    }

    public function testChunkValuesKeysAreNotPreserved(): void
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
