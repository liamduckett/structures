<?php

namespace Tests\Dictionary;

use Liamduckett\Structures\Dictionary;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;
use PHPUnit\Framework\TestCase;
use Tests\Dictionary\Concerns\TestsDictionaries;

/**
 * @internal
 *
 * @coversNothing
 */
class RetrievingTest extends TestCase
{
    use TestsDictionaries;

    // get ---

    public function testGetReturnsTheValueForAnExistingKey(): void
    {
        $value = Dictionary::make()
            ->merge(['foo' => 1, 'bar' => 2])
            ->get('foo')
        ;

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
        $keys = Dictionary::make()
            ->merge(['foo' => 1, 'bar' => 2])
            ->keys()
        ;

        $this->assertSame(['foo', 'bar'], $keys);
    }

    public function testKeysReturnsEmptyForAnEmptyDictionary(): void
    {
        $this->assertSame([], Dictionary::make()->keys());
    }

    // items ---

    public function testItemsReturnsAllItems(): void
    {
        $items = Dictionary::make()
            ->merge(['foo' => 1, 'bar' => 2])
            ->items()
        ;

        $this->assertSame(['foo' => 1, 'bar' => 2], $items);
    }

    public function testItemsReturnsEmptyForAnEmptyDictionary(): void
    {
        $this->assertSame([], Dictionary::make()->items());
    }

    // values ---

    public function testValuesStripsStringKeys(): void
    {
        $values = Dictionary::make()
            ->merge(['foo' => 1, 'bar' => 2])
            ->values()
        ;

        $this->assertSame([1, 2], $values);
    }

    public function testValuesReturnsEmptyForAnEmptyDictionary(): void
    {
        $this->assertSame([], Dictionary::make()->values());
    }
}
