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

    // Slice ---

    public function testSliceExtractsItemsAtOffset(): void
    {
        $sequence = Sequence::make([1, 2, 3, 4, 5])->slice(1, 3);

        $this->assertSequence($sequence, [2, 3, 4]);
    }

    public function testSliceWithNullLengthGoesToEnd(): void
    {
        $sequence = Sequence::make([1, 2, 3, 4, 5])->slice(2);

        $this->assertSequence($sequence, [3, 4, 5]);
    }

    public function testSliceASingleItem(): void
    {
        $sequence = Sequence::make([1, 2, 3])->slice(1, 1);

        $this->assertSequence($sequence, [2]);
    }

    public function testSliceReIndexes(): void
    {
        $sequence = Sequence::make([1, 2, 3])->slice(1, 2);

        $this->assertSame(2, $sequence->get(0));
    }

    public function testSliceOnAnEmptySequenceReturnsEmpty(): void
    {
        $this->assertSequence(Sequence::make()->slice(0), []);
    }

    public function testSliceIsImmutable(): void
    {
        $original = Sequence::make([1, 2, 3]);

        $original->slice(0, 1);

        $this->assertSequence($original, [1, 2, 3]);
    }

    public function testSliceIsLazy(): void
    {
        $calls = 0;

        $sequence = new Sequence(static function () use (&$calls) {
            foreach ([1, 2, 3] as $value) {
                ++$calls;

                yield $value;
            }
        });

        $sequence->slice(0, 1);

        $this->assertSame(0, $calls);
    }
}
