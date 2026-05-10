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
class MappingTest extends TestCase
{
    use TestsStructures;

    // Map ---

    public function testMapTransformsValues(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $mapped = $sequence->map(fn (int $value) => $value * 2);

        $this->assertSequence($mapped, [2, 4, 6]);
    }

    public function testMapPassesIndexToCallable(): void
    {
        $sequence = Sequence::make([10, 20, 30]);

        $mapped = $sequence->map(fn (int $value, int $index) => $index);

        $this->assertSequence($mapped, [0, 1, 2]);
    }

    public function testMapPreservesOrder(): void
    {
        $sequence = Sequence::make([3, 1, 2]);

        $mapped = $sequence->map(fn (int $value) => $value * 10);

        $this->assertSequence($mapped, [30, 10, 20]);
    }

    public function testMapIsImmutable(): void
    {
        $original = Sequence::make([1, 2, 3]);

        $mapped = $original->map(fn (int $value) => $value * 2);

        $this->assertNotSame($original, $mapped);

        $this->assertSequence($original, [1, 2, 3]);
    }
}
