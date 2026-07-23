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
class AddingTest extends TestCase
{
    use TestsStructures;

    // Set ---

    public function testSetAddsANewKey(): void
    {
        $dictionary = Dictionary::make()
            ->set('foo', 1)
            ->set('bar', 2);

        $this->assertDictionary($dictionary, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    public function testSetOverwritesAnExistingKey(): void
    {
        $dictionary = Dictionary::make()
            ->set('foo', 1)
            ->set('foo', 99);

        $this->assertDictionary($dictionary, [
            'foo' => 99,
        ]);
    }

    public function testSetIsImmutable(): void
    {
        $original = Dictionary::make(['foo' => 1]);

        $modified = $original->set('bar', 2);

        $this->assertNotSame($original, $modified);

        $this->assertDictionary($original, [
            'foo' => 1,
        ]);
    }

    public function testSetIsLazy(): void
    {
        $counter = new CallCounter();

        $dictionary = new Dictionary(static function () use ($counter) {
            foreach (['foo' => 1, 'bar' => 2] as $key => $value) {
                $counter->increment();

                yield $key => $value;
            }
        });

        $modified = $dictionary->set('baz', 3);

        $this->assertCount(0, $counter);

        $modified->array();

        $this->assertCount(2, $counter);
    }

    public function testSetDictionaryCanBeIteratedTwice(): void
    {
        $dictionary = Dictionary::make(['foo' => 1])->set('bar', 2);

        $this->assertIteratesTwice($dictionary, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }
}
