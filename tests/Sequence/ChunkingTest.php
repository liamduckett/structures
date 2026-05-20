<?php

namespace Tests\Sequence;

use Liamduckett\Structures\Sequence;
use PHPUnit\Framework\TestCase;
use Tests\Concerns\TestsStructures;

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

        $this->assertCount(2, $chunks);
        $this->assertSequence($chunks->get(0), [1, 2]);
        $this->assertSequence($chunks->get(1), [3, 4]);
    }

    public function testChunkHandlesLastChunkSmallerThanSize(): void
    {
        $sequence = Sequence::make([1, 2, 3, 4, 5]);

        $chunks = $sequence->chunk(2);

        $this->assertCount(3, $chunks);
        $this->assertSequence($chunks->get(0), [1, 2]);
        $this->assertSequence($chunks->get(1), [3, 4]);
        $this->assertSequence($chunks->get(2), [5]);
    }

    public function testChunkReindexesInnerSequences(): void
    {
        $sequence = Sequence::make([10, 20, 30, 40]);

        $chunks = $sequence->chunk(2);

        $this->assertSame(10, $chunks->get(0)->get(0));
        $this->assertSame(20, $chunks->get(0)->get(1));
        $this->assertSame(30, $chunks->get(1)->get(0));
        $this->assertSame(40, $chunks->get(1)->get(1));
    }

    public function testChunkWithSizeEqualToLength(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $chunks = $sequence->chunk(3);

        $this->assertCount(1, $chunks);
        $this->assertSequence($chunks->get(0), [1, 2, 3]);
    }

    public function testChunkWithSizeGreaterThanLength(): void
    {
        $sequence = Sequence::make([1, 2]);

        $chunks = $sequence->chunk(10);

        $this->assertCount(1, $chunks);
        $this->assertSequence($chunks->get(0), [1, 2]);
    }

    public function testChunkIsImmutable(): void
    {
        $original = Sequence::make([1, 2, 3, 4]);

        $original->chunk(2);

        $this->assertSequence($original, [1, 2, 3, 4]);
    }

    public function testChunkIsLazy(): void
    {
        $calls = 0;

        $sequence = new Sequence(static function () use (&$calls) {
            foreach ([1, 2, 3, 4] as $value) {
                ++$calls;

                yield $value;
            }
        });

        $sequence->chunk(2);

        $this->assertSame(0, $calls);
    }
}
