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

    public function testPushAppendsItems(): void
    {
        $sequence = Sequence::make([1, 2])->push([3, 4]);

        $this->assertSequence($sequence, [1, 2, 3, 4]);
    }

    public function testPushAcceptsAnIterable(): void
    {
        $sequence = Sequence::make([1, 2])
            ->push(new ArrayIterator([3, 4]));

        $this->assertSequence($sequence, [1, 2, 3, 4]);
    }

    public function testPushIsImmutable(): void
    {
        $original = Sequence::make([1, 2]);

        $modified = $original->push([3]);

        $this->assertNotSame($original, $modified);

        $this->assertSequence($original, [1, 2]);
    }

    // Prepend ---

    public function testPrependAddsItemsToFront(): void
    {
        $sequence = Sequence::make([3, 4])->prepend([1, 2]);

        $this->assertSequence($sequence, [1, 2, 3, 4]);
    }

    public function testPrependAcceptsAnIterable(): void
    {
        $sequence = Sequence::make([3, 4])->prepend(new ArrayIterator([1, 2]));

        $this->assertSequence($sequence, [1, 2, 3, 4]);
    }

    public function testPrependIsImmutable(): void
    {
        $original = Sequence::make([2, 3]);

        $modified = $original->prepend([1]);

        $this->assertNotSame($original, $modified);

        $this->assertSequence($original, [2, 3]);
    }

    public function testPrependedSequenceCanBeIteratedTwice(): void
    {
        $sequence = Sequence::make([3, 4])->prepend([1, 2]);

        $this->assertIteratesTwice($sequence, [1, 2, 3, 4]);
    }

    // Merge ---

    public function testMergeCombinesTwoSequences(): void
    {
        $sequence = Sequence::make([1, 2])->merge([3, 4]);

        $this->assertSequence($sequence, [1, 2, 3, 4]);
    }

    public function testMergeAcceptsAnIterable(): void
    {
        $sequence = Sequence::make([1, 2])->merge(new ArrayIterator([3, 4]));

        $this->assertSequence($sequence, [1, 2, 3, 4]);
    }

    public function testMergeIsImmutable(): void
    {
        $original = Sequence::make([1, 2]);

        $merged = $original->merge([3, 4]);

        $this->assertNotSame($original, $merged);

        $this->assertSequence($original, [1, 2]);
    }

    public function testMergeIsLazy(): void
    {
        $calls = 0;

        $sequence = new Sequence(static function () use (&$calls) {
            foreach ([1, 2] as $value) {
                ++$calls;

                yield $value;
            }
        });

        $sequence->merge([3, 4]);

        $this->assertSame(0, $calls);
    }

    public function testMergedSequenceCanBeIteratedTwice(): void
    {
        $sequence = Sequence::make([1, 2])->merge([3, 4]);

        $this->assertIteratesTwice($sequence, [1, 2, 3, 4]);
    }
}
