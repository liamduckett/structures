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
class SortingTest extends TestCase
{
    use TestsStructures;

    // SortKeyAscending ---

    public function testSortKeyAscendingSortsKeysAlphabetically(): void
    {
        $dictionary = Dictionary::make([
            'banana' => 2,
            'apple' => 1,
            'cherry' => 3,
        ]);

        $sorted = $dictionary->sortKeyAscending();

        $this->assertDictionary($sorted, [
            'apple' => 1,
            'banana' => 2,
            'cherry' => 3,
        ]);
    }

    public function testSortKeyAscendingIsImmutable(): void
    {
        $original = Dictionary::make([
            'banana' => 2,
            'apple' => 1,
        ]);

        $sorted = $original->sortKeyAscending();

        $this->assertNotSame($original, $sorted);

        $this->assertDictionary($original, [
            'banana' => 2,
            'apple' => 1,
        ]);
    }
}
