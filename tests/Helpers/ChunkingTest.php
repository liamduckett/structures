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

    public function testChunkYieldsAllItemsWhenEachChunkIsFullyConsumed(): void
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

    public function testChunkSkipsRemainingItemsWhenEachChunkIsPartiallyConsumed(): void
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

    public function testChunkYieldsEveryChunkWhenNoChunkIsConsumed(): void
    {
        $chunks = iterable_chunk([1, 2, 3, 4, 5], 2);

        $count = 0;

        foreach ($chunks as $_) {
            ++$count;
        }

        $this->assertSame(3, $count);
    }

    public function testChunkAcceptsGenerators(): void
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

    public function testChunkWithSizeEqualToLengthYieldsOneChunk(): void
    {
        $chunks = iterable_chunk([1, 2, 3], 3);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2, 2 => 3]], $result);
    }

    public function testChunkWithSizeGreaterThanLengthYieldsOneChunk(): void
    {
        $chunks = iterable_chunk([1, 2], 10);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2]], $result);
    }

    public function testChunkPreservesKeys(): void
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

    public function testChunkPreservesDuplicateKeys(): void
    {
        $generator = (static function () {
            yield 'a' => 1;

            yield 'a' => 2;

            yield 'b' => 3;

            yield 'c' => 4;
        })();

        $keys = [];
        $values = [];

        foreach (iterable_chunk($generator, 2) as $chunk) {
            foreach ($chunk as $key => $value) {
                $keys[] = $key;
                $values[] = $value;
            }

            break;
        }

        $this->assertSame(['a', 'a'], $keys);
        $this->assertSame([1, 2], $values);
    }

    public function testChunkSubGeneratorsCanBeConsumedAfterOuterLoop(): void
    {
        $chunks = iterable_chunk([1, 2, 3, 4, 5, 6], 2);

        $collected = [];

        foreach ($chunks as $chunk) {
            $collected[] = $chunk;
        }

        $result = [];

        foreach ($collected as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([
            [0 => 1, 1 => 2],
            [2 => 3, 3 => 4],
            [4 => 5, 5 => 6],
        ], $result);
    }

    // Chunk Values ---

    public function testChunkValuesYieldsAllItemsWhenEachChunkIsFullyConsumed(): void
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

    public function testChunkValuesSkipsRemainingItemsWhenEachChunkIsPartiallyConsumed(): void
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

    public function testChunkValuesYieldsEveryChunkWhenNoChunkIsConsumed(): void
    {
        $chunks = iterable_chunk_values([1, 2, 3, 4, 5], 2);

        $count = 0;

        foreach ($chunks as $_) {
            ++$count;
        }

        $this->assertSame(3, $count);
    }

    public function testChunkValuesAcceptsGenerators(): void
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

    public function testChunkValuesWithSizeEqualToLengthYieldsOneChunk(): void
    {
        $chunks = iterable_chunk_values([1, 2, 3], 3);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2, 2 => 3]], $result);
    }

    public function testChunkValuesWithSizeGreaterThanLengthYieldsOneChunk(): void
    {
        $chunks = iterable_chunk_values([1, 2], 10);

        $result = [];

        foreach ($chunks as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([[0 => 1, 1 => 2]], $result);
    }

    public function testChunkValuesDiscardsKeys(): void
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

    public function testChunkValuesSubGeneratorsCanBeConsumedAfterOuterLoop(): void
    {
        $chunks = iterable_chunk_values([1, 2, 3, 4, 5, 6], 2);

        $collected = [];

        foreach ($chunks as $chunk) {
            $collected[] = $chunk;
        }

        $result = [];

        foreach ($collected as $chunk) {
            $result[] = iterator_to_array($chunk);
        }

        $this->assertSame([
            [0 => 1, 1 => 2],
            [0 => 3, 1 => 4],
            [0 => 5, 1 => 6],
        ], $result);
    }
}
