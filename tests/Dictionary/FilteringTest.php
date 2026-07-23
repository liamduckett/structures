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
class FilteringTest extends TestCase
{
    use TestsStructures;

    // filter ---

    public function testFilterByValue(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
            'baz' => 3,
        ]);

        $filtered = $dictionary->filter(static fn (int $value) => $value > 1);

        $this->assertDictionary($filtered, [
            'bar' => 2,
            'baz' => 3,
        ]);
    }

    public function testFilterByKey(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
            'baz' => 3,
        ]);

        $filtered = $dictionary->filter(static fn (int $value, string $key) => 'foo' === $key);

        $this->assertDictionary($filtered, [
            'foo' => 1,
        ]);
    }

    public function testFilterPreservesKeys(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
            'baz' => 3,
        ]);

        $filtered = $dictionary->filter(static fn (int $value) => 2 !== $value);

        $this->assertDictionary($filtered, [
            'foo' => 1,
            'baz' => 3,
        ]);
    }

    public function testFilterReturnsEmptyWhenNothingMatches(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $filtered = $dictionary->filter(static fn (int $value) => $value > 99);

        $this->assertDictionary($filtered, []);
    }

    public function testFilterIsImmutable(): void
    {
        $original = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $filtered = $original->filter(static fn (int $value) => $value > 1);

        $this->assertNotSame($original, $filtered);

        $this->assertDictionary($original, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    public function testFilterIsLazy(): void
    {
        $counter = new CallCounter();

        $dictionary = new Dictionary(static function () use ($counter) {
            foreach (['foo' => 1, 'bar' => 2] as $key => $value) {
                $counter->increment();

                yield $key => $value;
            }
        });

        $filtered = $dictionary->filter(static fn (int $value) => $value > 1);

        $this->assertCount(0, $counter);

        $filtered->array();

        $this->assertCount(2, $counter);
    }

    public function testFilteredDictionaryCanBeIteratedTwice(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $filtered = $dictionary->filter(static fn (int $value) => 0 === $value % 2);

        $this->assertIteratesTwice($filtered, ['bar' => 2]);
    }

    // search ---

    public function testSearchReturnsAllMatchingKeys(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
            'baz' => 1,
        ]);

        $keys = $dictionary->search(1);

        $this->assertSequence($keys, ['foo', 'baz']);
    }

    public function testSearchReturnsEmptyWhenValueNotFound(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $keys = $dictionary->search(99);

        $this->assertSequence($keys, []);
    }

    public function testSearchUsesStrictComparison(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => '1',
        ]);

        $keys = $dictionary->search(1);

        $this->assertSequence($keys, ['foo']);
    }

    public function testSearchIsLazy(): void
    {
        $counter = new CallCounter();

        $dictionary = new Dictionary(static function () use ($counter) {
            foreach (['foo' => 1, 'bar' => 2, 'baz' => 1] as $key => $value) {
                $counter->increment();

                yield $key => $value;
            }
        });

        $keys = $dictionary->search(1);

        $this->assertCount(0, $counter);

        $keys->array();

        $this->assertCount(3, $counter);
    }

    public function testSearchedSequenceCanBeIteratedTwice(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
            'baz' => 1,
        ]);

        $keys = $dictionary->search(1);

        $this->assertIteratesTwice($keys, ['foo', 'baz']);
    }
}
