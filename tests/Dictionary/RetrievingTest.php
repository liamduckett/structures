<?php

namespace Tests\Dictionary;

use Liamduckett\Structures\Dictionary;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;
use PHPUnit\Framework\TestCase;
use Tests\Concerns\TestsStructures;

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
}
