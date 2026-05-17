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
class FilteringTest extends TestCase
{
    use TestsStructures;

    // Filter ---

    public function testFilterByValue(): void
    {
        $sequence = Sequence::make([1, 2, 3, 4]);

        $filtered = $sequence->filter(fn (int $value) => $value > 2);

        $this->assertSequence($filtered, [3, 4]);
    }

    public function testFilterByIndex(): void
    {
        $sequence = Sequence::make([10, 20, 30, 40]);

        $filtered = $sequence->filter(fn (int $value, int $index) => 0 === $index % 2);

        $this->assertSequence($filtered, [10, 30]);
    }

    public function testFilterReIndexes(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $filtered = $sequence->filter(fn (int $value) => $value > 1);

        $this->assertSame(2, $filtered->get(0));
    }

    public function testFilterReturnsEmptyWhenNothingMatches(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $filtered = $sequence->filter(fn (int $value) => $value > 99);

        $this->assertSequence($filtered, []);
    }

    public function testFilterIsImmutable(): void
    {
        $original = Sequence::make([1, 2, 3]);

        $filtered = $original->filter(fn (int $value) => $value > 1);

        $this->assertNotSame($original, $filtered);

        $this->assertSequence($original, [1, 2, 3]);
    }

    public function testFilterIsLazy(): void
    {
        $calls = 0;

        Sequence::make([1, 2, 3])->filter(function (int $value) use (&$calls): bool {
            ++$calls;

            return $value > 1;
        });

        $this->assertSame(0, $calls);
    }
}
