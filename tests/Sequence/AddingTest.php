<?php

namespace Tests\Sequence;

use ArrayIterator;
use Liamduckett\Structures\Sequence;
use PHPUnit\Framework\TestCase;
use Tests\Concerns\TestsStructures;

/**
 * @internal
 *
 * @coversNothing
 */
class AddingTest extends TestCase
{
    use TestsStructures;

    // Push ---

    public function testPushAppendsAnItem(): void
    {
        $sequence = Sequence::make([1, 2])->push(3);

        $this->assertSequence($sequence, [1, 2, 3]);
    }

    public function testPushIsImmutable(): void
    {
        $original = Sequence::make([1, 2]);

        $modified = $original->push(3);

        $this->assertNotSame($original, $modified);

        $this->assertSequence($original, [1, 2]);
    }

    // Prepend ---

    public function testPrependAddsItemToFront(): void
    {
        $sequence = Sequence::make([2, 3])->prepend(1);

        $this->assertSequence($sequence, [1, 2, 3]);
    }

    public function testPrependIsImmutable(): void
    {
        $original = Sequence::make([2, 3]);

        $modified = $original->prepend(1);

        $this->assertNotSame($original, $modified);

        $this->assertSequence($original, [2, 3]);
    }

    // Merge ---

    public function testMergeCombinesTwoSequences(): void
    {
        $sequence = Sequence::make([1, 2])->merge([3, 4]);

        $this->assertSequence($sequence, [1, 2, 3, 4]);
    }

    public function testMergeAcceptsAnIterable(): void
    {
        $sequence = Sequence::make([1, 2])
            ->merge(new ArrayIterator([3, 4]));

        $this->assertSequence($sequence, [1, 2, 3, 4]);
    }

    public function testMergeIsImmutable(): void
    {
        $original = Sequence::make([1, 2]);

        $merged = $original->merge([3, 4]);

        $this->assertNotSame($original, $merged);

        $this->assertSequence($original, [1, 2]);
    }
}
