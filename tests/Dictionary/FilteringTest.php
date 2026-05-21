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
}
