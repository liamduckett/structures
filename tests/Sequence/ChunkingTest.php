<?php

namespace Tests\Sequence;

use Liamduckett\Structures\Sequence;
use PHPUnit\Framework\TestCase;
use Tests\Concerns\TestsStructures;
use Tests\Support\CallCounter;

/**
 * @internal
 *
 * @coversNothing
 */
class ChunkingTest extends TestCase
{
    use TestsStructures;

    // Chunk ---

    public function testChunkSplitsIntoCorrectSizes(): void
    {
        $sequence = Sequence::make([1, 2, 3, 4]);

        $chunks = $sequence->chunk(2);

        $this->assertSequenceOfSequences($chunks, [
            [1, 2],
            [3, 4],
        ]);
    }

    public function testChunkHandlesLastChunkSmallerThanSize(): void
    {
        $sequence = Sequence::make([1, 2, 3, 4, 5]);

        $chunks = $sequence->chunk(2);

        $this->assertSequenceOfSequences($chunks, [
            [1, 2],
            [3, 4],
            [5],
        ]);
    }

    public function testChunkReindexesInnerSequences(): void
    {
        $sequence = Sequence::make([10, 20, 30, 40]);

        $chunks = $sequence->chunk(2);

        $this->assertSequenceOfSequences($chunks, [
            [10, 20],
            [30, 40],
        ]);
    }

    public function testChunkWithSizeEqualToLength(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $chunks = $sequence->chunk(3);

        $this->assertSequenceOfSequences($chunks, [
            [1, 2, 3],
        ]);
    }

    public function testChunkWithSizeGreaterThanLength(): void
    {
        $sequence = Sequence::make([1, 2]);

        $chunks = $sequence->chunk(10);

        $this->assertSequenceOfSequences($chunks, [
            [1, 2],
        ]);
    }

    public function testChunkIsImmutable(): void
    {
        $original = Sequence::make([1, 2, 3, 4]);

        $original->chunk(2);

        $this->assertSequence($original, [1, 2, 3, 4]);
    }

    public function testChunkIsLazy(): void
    {
        $counter = new CallCounter();

        $sequence = new Sequence(static function () use ($counter) {
            foreach ([1, 2, 3, 4] as $value) {
                $counter->increment();

                yield $value;
            }
        });

        $chunked = $sequence->chunk(2);

        $this->assertCount(0, $counter);

        foreach ($chunked as $chunk) {
            $chunk->array();
        }

        $this->assertCount(4, $counter);
    }

    public function testChunkedSequenceCanBeIteratedTwice(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $chunks = $sequence
            ->chunk(2)
            ->map(static fn (Sequence $chunk) => $chunk->array());

        $this->assertIteratesTwice($chunks, [
            [1, 2],
            [3],
        ]);
    }
}
