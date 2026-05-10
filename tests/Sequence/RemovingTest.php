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
class RemovingTest extends TestCase
{
    use TestsStructures;

    // Pop ---

    public function testPopRemovesTheLastItem(): void
    {
        $sequence = Sequence::make([1, 2, 3])->pop();

        $this->assertSequence($sequence, [1, 2]);
    }

    public function testPopOnAnEmptySequenceIsANoop(): void
    {
        $this->assertSequence(Sequence::make()->pop(), []);
    }

    public function testPopIsImmutable(): void
    {
        $original = Sequence::make([1, 2, 3]);

        $modified = $original->pop();

        $this->assertNotSame($original, $modified);

        $this->assertSequence($original, [1, 2, 3]);
    }

    // Shift ---

    public function testShiftRemovesTheFirstItem(): void
    {
        $sequence = Sequence::make([1, 2, 3])->shift();

        $this->assertSequence($sequence, [2, 3]);
    }

    public function testShiftOnAnEmptySequenceIsANoop(): void
    {
        $this->assertSequence(Sequence::make()->shift(), []);
    }

    public function testShiftIsImmutable(): void
    {
        $original = Sequence::make([1, 2, 3]);

        $modified = $original->shift();

        $this->assertNotSame($original, $modified);

        $this->assertSequence($original, [1, 2, 3]);
    }

    public function testShiftReIndexes(): void
    {
        $sequence = Sequence::make([1, 2, 3])->shift();

        $this->assertSame(2, $sequence->get(0));
    }
}
