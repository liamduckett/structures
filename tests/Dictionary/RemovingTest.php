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
class RemovingTest extends TestCase
{
    use TestsStructures;

    public function testRemoveRemovesExistingKey(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $withoutFoo = $dictionary->remove('foo');

        $this->assertDictionary($withoutFoo, [
            'bar' => 2,
        ]);
    }

    public function testRemoveOnMissingKeyIsNoop(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $withoutBar = $dictionary->remove('bar');

        $this->assertDictionary($withoutBar, [
            'foo' => 1,
        ]);
    }

    public function testRemoveIsImmutable(): void
    {
        $original = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $withoutFoo = $original->remove('foo');

        $this->assertNotSame($original, $withoutFoo);

        $this->assertDictionary($original, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    public function testRemoveIsLazy(): void
    {
        $counter = new CallCounter();

        $dictionary = new Dictionary(static function () use ($counter) {
            foreach (['foo' => 1, 'bar' => 2] as $key => $value) {
                $counter->increment();

                yield $key => $value;
            }
        });

        $withoutFoo = $dictionary->remove('foo');

        $this->assertCount(0, $counter);

        $withoutFoo->array();

        $this->assertCount(2, $counter);
    }

    public function testRemoveCanBeIteratedTwice(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $withoutFoo = $dictionary->remove('foo');

        $this->assertIteratesTwice($withoutFoo, ['bar' => 2]);
    }
}
