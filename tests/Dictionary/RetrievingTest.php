<?php

namespace Tests\Dictionary;

use Liamduckett\Structures\Dictionary;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;
use PHPUnit\Framework\TestCase;
use Tests\Concerns\TestsStructures;
use Tests\Support\CallCounter;

/**
 * @internal
 *
 * @coversNothing
 */
class RetrievingTest extends TestCase
{
    use TestsStructures;

    // Get ---

    public function testGetReturnsTheValueForExistingKey(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $this->assertSame(1, $dictionary->get('foo'));
    }

    public function testGetThrowsForMissingKey(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        Dictionary::make()->set('foo', 1)->get('bar');
    }

    public function testGetUsesStrictComparison(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        Dictionary::make([1 => 'foo'])->get('1'); // @phpstan-ignore argument.type
    }

    public function testGetReturnsNullForKeyHoldingNull(): void
    {
        $dictionary = Dictionary::make([
            'foo' => null,
            'bar' => 1,
        ]);

        $this->assertNull($dictionary->get('foo'));
    }

    // Keys ---

    public function testKeysReturnsAllKeys(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $this->assertSequence($dictionary->keys(), ['foo', 'bar']);
    }

    public function testKeysReturnsEmptyForEmptyDictionary(): void
    {
        $keys = Dictionary::make()->keys();

        $this->assertSequence($keys, []);
    }

    public function testKeysIsImmutable(): void
    {
        $original = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $original->keys();

        $this->assertDictionary($original, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    public function testKeysIsLazy(): void
    {
        $counter = new CallCounter();

        $dictionary = new Dictionary(static function () use ($counter) {
            foreach (['foo' => 1, 'bar' => 2] as $key => $value) {
                $counter->increment();

                yield $key => $value;
            }
        });

        $keys = $dictionary->keys();

        $this->assertCount(0, $counter);

        $keys->array();

        $this->assertCount(2, $counter);
    }

    public function testKeysCanBeIteratedTwice(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $keys = $dictionary->keys();

        $this->assertIteratesTwice($keys, ['foo', 'bar']);
    }

    // Values ---

    public function testValuesStripsStringKeys(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $this->assertSequence($dictionary->values(), [1, 2]);
    }

    public function testValuesReturnsEmptyForEmptyDictionary(): void
    {
        $values = Dictionary::make()->values();

        $this->assertSequence($values, []);
    }

    public function testValuesIsImmutable(): void
    {
        $original = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $original->values();

        $this->assertDictionary($original, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    public function testValuesIsLazy(): void
    {
        $counter = new CallCounter();

        $dictionary = new Dictionary(static function () use ($counter) {
            foreach (['foo' => 1, 'bar' => 2] as $key => $value) {
                $counter->increment();

                yield $key => $value;
            }
        });

        $values = $dictionary->values();

        $this->assertCount(0, $counter);

        $values->array();

        $this->assertCount(2, $counter);
    }

    public function testValuesCanBeIteratedTwice(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $values = $dictionary->values();

        $this->assertIteratesTwice($values, [1, 2]);
    }
}
