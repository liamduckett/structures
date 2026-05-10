<?php

namespace Tests\Dictionary;

use Liamduckett\Structures\Dictionary;
use PHPUnit\Framework\TestCase;
use Tests\Dictionary\Concerns\TestsDictionaries;

/**
 * @internal
 *
 * @coversNothing
 */
class FilteringTest extends TestCase
{
    use TestsDictionaries;

    // filter ---

    public function testFilterByValue(): void
    {
        $dictionary = Dictionary::make(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        $filtered = $dictionary->filter(fn (int $value) => $value > 1);

        $this->assertDictionary($filtered, [
            'bar' => 2,
            'baz' => 3,
        ]);
    }

    public function testFilterByKey(): void
    {
        $dictionary = Dictionary::make(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        $filtered = $dictionary->filter(fn (int $value, string $key) => 'foo' === $key);

        $this->assertDictionary($filtered, [
            'foo' => 1,
        ]);
    }

    public function testFilterPreservesKeys(): void
    {
        $dictionary = Dictionary::make(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        $filtered = $dictionary->filter(fn (int $value) => 2 !== $value);

        $this->assertDictionary($filtered, [
            'foo' => 1,
            'baz' => 3,
        ]);
    }

    public function testFilterReturnsEmptyWhenNothingMatches(): void
    {
        $dictionary = Dictionary::make(['foo' => 1, 'bar' => 2]);

        $filtered = $dictionary->filter(fn (int $value) => $value > 99);

        $this->assertDictionary($filtered, []);
    }

    public function testFilterIsImmutable(): void
    {
        $original = Dictionary::make(['foo' => 1, 'bar' => 2]);

        $filtered = $original->filter(fn (int $value) => $value > 1);

        $this->assertNotSame($original, $filtered);

        $this->assertDictionary($original, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    // search ---

    public function testSearchReturnsAllMatchingKeys(): void
    {
        $dictionary = Dictionary::make()->merge(['foo' => 1, 'bar' => 2, 'baz' => 1]);

        $keys = $dictionary->search(1);

        $this->assertSame(['foo', 'baz'], $keys);
    }

    public function testSearchReturnsEmptyWhenValueNotFound(): void
    {
        $dictionary = Dictionary::make()->merge(['foo' => 1, 'bar' => 2]);

        $keys = $dictionary->search(99);

        $this->assertSame([], $keys);
    }

    public function testSearchUsesStrictComparison(): void
    {
        $dictionary = Dictionary::make()->merge(['foo' => 1, 'bar' => 2]);

        $keys = $dictionary->search('1');

        $this->assertSame([], $keys);
    }
}
