<?php

namespace Tests\Sequence;

use ArrayIterator;
use Liamduckett\Structures\Sequence;
use PHPUnit\Framework\TestCase;
use Tests\Concerns\TestsStructures;
use Tests\Support\CallCounter;

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

    public function testPushAcceptsIterables(): void
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

    public function testPushIsLazy(): void
    {
        $counter = new CallCounter();

        $sequence = new Sequence(static function () use ($counter) {
            foreach ([1, 2] as $value) {
                $counter->increment();

                yield $value;
            }
        });

        $pushed = $sequence->push([3, 4]);

        $this->assertCount(0, $counter);

        $pushed->array();

        $this->assertCount(2, $counter);
    }

    public function testPushCanBeIteratedTwice(): void
    {
        $sequence = Sequence::make([1, 2])->push([3, 4]);

        $this->assertIteratesTwice($sequence, [1, 2, 3, 4]);
    }

    // Prepend ---

    public function testPrependAddsItemsToFront(): void
    {
        $sequence = Sequence::make([3, 4])->prepend([1, 2]);

        $this->assertSequence($sequence, [1, 2, 3, 4]);
    }

    public function testPrependAcceptsIterables(): void
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

    public function testPrependIsLazy(): void
    {
        $counter = new CallCounter();

        $sequence = new Sequence(static function () use ($counter) {
            foreach ([2, 3] as $value) {
                $counter->increment();

                yield $value;
            }
        });

        $prepended = $sequence->prepend([1]);

        $this->assertCount(0, $counter);

        $prepended->array();

        $this->assertCount(2, $counter);
    }

    public function testPrependCanBeIteratedTwice(): void
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

    public function testMergeAcceptsIterables(): void
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
        $counter = new CallCounter();

        $sequence = new Sequence(static function () use ($counter) {
            foreach ([1, 2] as $value) {
                $counter->increment();

                yield $value;
            }
        });

        $merged = $sequence->merge([3, 4]);

        $this->assertCount(0, $counter);

        $merged->array();

        $this->assertCount(2, $counter);
    }

    public function testMergeCanBeIteratedTwice(): void
    {
        $sequence = Sequence::make([1, 2])->merge([3, 4]);

        $this->assertIteratesTwice($sequence, [1, 2, 3, 4]);
    }
}
