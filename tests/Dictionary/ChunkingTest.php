<?php

namespace Tests\Dictionary;

use Liamduckett\Structures\Dictionary;
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
        $dictionary = Dictionary::make(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]);

        $chunks = $dictionary->chunk(2);

        $this->assertCount(2, $chunks);
        $this->assertDictionary($chunks->get(0), ['a' => 1, 'b' => 2]);
        $this->assertDictionary($chunks->get(1), ['c' => 3, 'd' => 4]);
    }

    public function testChunkHandlesLastChunkSmallerThanSize(): void
    {
        $dictionary = Dictionary::make(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5]);

        $chunks = $dictionary->chunk(2);

        $this->assertCount(3, $chunks);
        $this->assertDictionary($chunks->get(0), ['a' => 1, 'b' => 2]);
        $this->assertDictionary($chunks->get(1), ['c' => 3, 'd' => 4]);
        $this->assertDictionary($chunks->get(2), ['e' => 5]);
    }

    public function testChunkPreservesKeys(): void
    {
        $dictionary = Dictionary::make(['foo' => 10, 'bar' => 20, 'baz' => 30]);

        $chunks = $dictionary->chunk(2);

        $this->assertSame(10, $chunks->get(0)->get('foo'));
        $this->assertSame(20, $chunks->get(0)->get('bar'));
        $this->assertSame(30, $chunks->get(1)->get('baz'));
    }

    public function testChunkWithSizeEqualToLength(): void
    {
        $dictionary = Dictionary::make(['a' => 1, 'b' => 2, 'c' => 3]);

        $chunks = $dictionary->chunk(3);

        $this->assertCount(1, $chunks);
        $this->assertDictionary($chunks->get(0), ['a' => 1, 'b' => 2, 'c' => 3]);
    }

    public function testChunkWithSizeGreaterThanLength(): void
    {
        $dictionary = Dictionary::make(['a' => 1, 'b' => 2]);

        $chunks = $dictionary->chunk(10);

        $this->assertCount(1, $chunks);
        $this->assertDictionary($chunks->get(0), ['a' => 1, 'b' => 2]);
    }

    public function testChunkIsImmutable(): void
    {
        $original = Dictionary::make(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]);

        $original->chunk(2);

        $this->assertDictionary($original, ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]);
    }

    public function testChunkIsLazy(): void
    {
        $calls = 0;

        $dictionary = new Dictionary(static function () use (&$calls) {
            foreach (['a' => 1, 'b' => 2] as $key => $value) {
                ++$calls;

                yield $key => $value;
            }
        });

        $dictionary->chunk(1);

        $this->assertSame(0, $calls);
    }
}
