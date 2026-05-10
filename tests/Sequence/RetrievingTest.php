<?php

namespace Tests\Sequence;

use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;
use Liamduckett\Structures\Sequence;
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

    // Get ---

    public function testGetReturnsTheValueAtAnIndex(): void
    {
        $value = Sequence::make([10, 20, 30])->get(1);

        $this->assertSame(20, $value);
    }

    public function testGetThrowsForAnInvalidIndex(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        Sequence::make([1, 2, 3])->get(99);
    }

    // First ---

    public function testFirstReturnsTheFirstItem(): void
    {
        $first = Sequence::make([10, 20, 30])->first();

        $this->assertSame(10, $first);
    }

    public function testFirstThrowsWhenEmpty(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        Sequence::make()->first();
    }

    // Last ---

    public function testLastReturnsTheLastItem(): void
    {
        $last = Sequence::make([10, 20, 30])->last();

        $this->assertSame(30, $last);
    }

    public function testLastThrowsWhenEmpty(): void
    {
        $this->expectException(OffsetDoesntExistException::class);

        Sequence::make()->last();
    }

    // Count ---

    public function testCountReturnsTheNumberOfItems(): void
    {
        $this->assertCount(3, Sequence::make([1, 2, 3]));
    }

    public function testCountReturnsZeroForAnEmptySequence(): void
    {
        $this->assertCount(0, Sequence::make());
    }
}
