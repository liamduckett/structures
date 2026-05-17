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
class CreatingTest extends TestCase
{
    use TestsStructures;

    public function testMakeWithAnArray(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $this->assertSequence($sequence, [1, 2, 3]);
    }

    public function testMakeWithAnIterable(): void
    {
        $sequence = Sequence::make(new ArrayIterator([1, 2, 3]));

        $this->assertSequence($sequence, [1, 2, 3]);
    }

    public function testMakeWithAGenerator(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;
        })();

        $sequence = Sequence::make($generator);

        $this->assertSequence($sequence, [1, 2]);
    }

    public function testMakeDefaultsToEmpty(): void
    {
        $this->assertSequence(Sequence::make(), []);
    }

    public function testMakeReIndexesKeys(): void
    {
        $sequence = Sequence::make([
            5 => 1,
            10 => 2,
        ]);

        $this->assertSequence($sequence, [1, 2]);
    }
}
