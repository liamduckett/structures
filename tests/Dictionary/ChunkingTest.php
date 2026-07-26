<?php

namespace Tests\Dictionary;

use Liamduckett\Structures\Dictionary;
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
        $dictionary = Dictionary::make([
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
        ]);

        $chunks = $dictionary->chunk(2);

        $this->assertSequenceOfDictionaries($chunks, [
            ['a' => 1, 'b' => 2],
            ['c' => 3, 'd' => 4],
        ]);
    }

    public function testChunkHandlesLastChunkSmallerThanSize(): void
    {
        $dictionary = Dictionary::make([
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
            'e' => 5,
        ]);

        $chunks = $dictionary->chunk(2);

        $this->assertSequenceOfDictionaries($chunks, [
            ['a' => 1, 'b' => 2],
            ['c' => 3, 'd' => 4],
            ['e' => 5],
        ]);
    }

    public function testChunkPreservesKeys(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 10,
            'bar' => 20,
            'baz' => 30,
        ]);

        $chunks = $dictionary->chunk(2);

        $this->assertSequenceOfDictionaries($chunks, [
            ['foo' => 10, 'bar' => 20],
            ['baz' => 30],
        ]);
    }

    public function testChunkPreservesDuplicateKeys(): void
    {
        $dictionary = new Dictionary(static function () {
            yield 'a' => 1;

            yield 'a' => 2;

            yield 'b' => 3;

            yield 'c' => 4;
        });

        $firstChunk = $dictionary->chunk(2)->first();

        $keys = [];
        $values = [];

        foreach ($firstChunk as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }

        $this->assertSame(['a', 'a'], $keys);
        $this->assertSame([1, 2], $values);
    }

    public function testChunkWithSizeEqualToLengthYieldsOneChunk(): void
    {
        $dictionary = Dictionary::make([
            'a' => 1,
            'b' => 2,
            'c' => 3,
        ]);

        $chunks = $dictionary->chunk(3);

        $this->assertSequenceOfDictionaries($chunks, [
            ['a' => 1, 'b' => 2, 'c' => 3],
        ]);
    }

    public function testChunkWithSizeGreaterThanLengthYieldsOneChunk(): void
    {
        $dictionary = Dictionary::make(['a' => 1, 'b' => 2]);

        $chunks = $dictionary->chunk(10);

        $this->assertSequenceOfDictionaries($chunks, [
            ['a' => 1, 'b' => 2],
        ]);
    }

    public function testChunkIsImmutable(): void
    {
        $original = Dictionary::make([
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
        ]);

        $original->chunk(2);

        $this->assertDictionary($original, [
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
        ]);
    }

    public function testChunkIsLazy(): void
    {
        $counter = new CallCounter();

        $dictionary = new Dictionary(static function () use ($counter) {
            foreach (['a' => 1, 'b' => 2] as $key => $value) {
                $counter->increment();

                yield $key => $value;
            }
        });

        $chunked = $dictionary->chunk(1);

        $this->assertCount(0, $counter);

        foreach ($chunked as $chunk) {
            $chunk->array();
        }

        $this->assertCount(2, $counter);
    }

    public function testChunkCanBeIteratedTwice(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
            'baz' => 3,
        ]);

        $chunks = $dictionary
            ->chunk(2)
            ->map(static fn (Dictionary $chunk) => $chunk->array());

        $this->assertIteratesTwice($chunks, [
            ['foo' => 1, 'bar' => 2],
            ['baz' => 3],
        ]);
    }
}
