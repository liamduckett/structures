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
        $value = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ])
            ->get('foo');

        $this->assertSame(1, $value);
    }

    public function testGetThrowsForAMissingKey(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        Dictionary::make()->set('foo', 1)->get('bar');
    }

    // keys ---

    public function testKeysReturnsAllKeys(): void
    {
        $keys = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ])
            ->keys();

        $this->assertSequence($keys, ['foo', 'bar']);
    }

    public function testKeysReturnsEmptyForAnEmptyDictionary(): void
    {
        $keys = Dictionary::make()->keys();

        $this->assertSequence($keys, []);
    }

    // values ---

    public function testValuesStripsStringKeys(): void
    {
        $values = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ])
            ->values();

        $this->assertSequence($values, [1, 2]);
    }

    public function testValuesReturnsEmptyForAnEmptyDictionary(): void
    {
        $values = Dictionary::make()->values();

        $this->assertSequence($values, []);
    }
}
