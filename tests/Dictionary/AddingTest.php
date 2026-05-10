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
class AddingTest extends TestCase
{
    use TestsDictionaries;

    // Set ---

    public function testSetAddsANewKey(): void
    {
        $dictionary = Dictionary::make()
            ->set('foo', 1)
            ->set('bar', 2)
        ;

        $this->assertDictionary($dictionary, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    public function testSetOverwritesAnExistingKey(): void
    {
        $dictionary = Dictionary::make()
            ->set('foo', 1)
            ->set('foo', 99)
        ;

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

    // Merge ---

    public function testMergeCombinesTwoDictionaries(): void
    {
        $dictionary = Dictionary::make()
            ->set('foo', 1)
            ->merge(['bar' => 2])
        ;

        $this->assertDictionary($dictionary, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    public function testMergePassedItemsWinOnConflict(): void
    {
        $dictionary = Dictionary::make()
            ->set('foo', 1)
            ->merge(['foo' => 99])
        ;

        $this->assertDictionary($dictionary, [
            'foo' => 99,
        ]);
    }

    public function testMergeAcceptsAnIterable(): void
    {
        $dictionary = Dictionary::make()
            ->set('foo', 1)
            ->merge($this->buildTypedIterator(['bar' => 2]))
        ;

        $this->assertDictionary($dictionary, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    public function testMergeIsImmutable(): void
    {
        $original = Dictionary::make(['foo' => 1]);

        $merged = $original->merge(['bar' => 2]);

        $this->assertNotSame($original, $merged);

        $this->assertDictionary($original, [
            'foo' => 1,
        ]);
    }
}
