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

    // get ---

    public function testGetReturnsTheValueForAnExistingKey(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $this->assertSame(1, $dictionary->get('foo'));
    }

    public function testGetThrowsForAMissingKey(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        Dictionary::make()->set('foo', 1)->get('bar');
    }

    // keys ---

    public function testKeysReturnsAllKeys(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $this->assertSequence($dictionary->keys(), ['foo', 'bar']);
    }

    public function testKeysReturnsEmptyForAnEmptyDictionary(): void
    {
        $keys = Dictionary::make()->keys();

        $this->assertSequence($keys, []);
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

    public function testKeysSequenceCanBeIteratedTwice(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $keys = $dictionary->keys();

        $this->assertIteratesTwice($keys, ['foo', 'bar']);
    }

    // values ---

    public function testValuesStripsStringKeys(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $this->assertSequence($dictionary->values(), [1, 2]);
    }

    public function testValuesReturnsEmptyForAnEmptyDictionary(): void
    {
        $values = Dictionary::make()->values();

        $this->assertSequence($values, []);
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

    public function testValuesSequenceCanBeIteratedTwice(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $values = $dictionary->values();

        $this->assertIteratesTwice($values, [1, 2]);
    }
}
