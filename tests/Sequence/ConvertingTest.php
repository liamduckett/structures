<?php

namespace Tests\Sequence;

use Liamduckett\Structures\Sequence;
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
        $array = Sequence::make([1, 2, 3])->array();

        $this->assertSame([1, 2, 3], $array);
    }

    // GetIterator ---

    public function testGetIteratorCanBeIterated(): void
    {
        $iterator = Sequence::make([1, 2, 3])->getIterator();

        $this->assertIterator($iterator, [1, 2, 3]);
    }

    // Iterator ---

    public function testIteratorCanBeIterated(): void
    {
        $iterator = Sequence::make([1, 2, 3])->iterator();

        $this->assertIterator($iterator, [1, 2, 3]);
    }
}
