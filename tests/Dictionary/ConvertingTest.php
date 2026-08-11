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
class ConvertingTest extends TestCase
{
    use TestsStructures;

    // Array ---

    public function testArrayReturnsTheUnderlyingArray(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $this->assertSame(['foo' => 1, 'bar' => 2], $dictionary->array());
    }

    // GetIterator ---

    public function testGetIteratorCanBeIterated(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $this->assertIterator($dictionary->getIterator(), ['foo' => 1, 'bar' => 2]);
    }

    // Iterator ---

    public function testIteratorCanBeIterated(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $this->assertIterator($dictionary->iterator(), ['foo' => 1, 'bar' => 2]);
    }
}
