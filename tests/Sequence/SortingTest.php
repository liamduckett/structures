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
class SortingTest extends TestCase
{
    use TestsStructures;

    // SortAscending ---

    public function testSortAscendingSortsValuesAscending(): void
    {
        $sequence = Sequence::make([3, 1, 4, 1, 5, 2]);

        $sorted = $sequence->sortAscending();

        $this->assertSequence($sorted, [1, 1, 2, 3, 4, 5]);
    }

    public function testSortAscendingIsImmutable(): void
    {
        $original = Sequence::make([3, 1, 2]);

        $sorted = $original->sortAscending();

        $this->assertNotSame($original, $sorted);

        $this->assertSequence($original, [3, 1, 2]);
    }

    // SortDescending ---

    public function testSortDescendingSortsValuesDescending(): void
    {
        $sequence = Sequence::make([3, 1, 4, 1, 5, 2]);

        $sorted = $sequence->sortDescending();

        $this->assertSequence($sorted, [5, 4, 3, 2, 1, 1]);
    }

    public function testSortDescendingIsImmutable(): void
    {
        $original = Sequence::make([3, 1, 2]);

        $sorted = $original->sortDescending();

        $this->assertNotSame($original, $sorted);

        $this->assertSequence($original, [3, 1, 2]);
    }
}
