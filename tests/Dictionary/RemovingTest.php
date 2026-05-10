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
class RemovingTest extends TestCase
{
    use TestsStructures;

    public function testRemoveRemovesAnExistingKey(): void
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

    public function testRemoveOnANonExistentKeyIsANoop(): void
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
}
