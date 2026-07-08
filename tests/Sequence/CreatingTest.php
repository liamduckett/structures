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
        $sequence = Sequence::make($this->buildTypedIterator([1, 2, 3]));

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

    public function testConstructorReIndexesClosureKeys(): void
    {
        $sequence = new Sequence(static function () {
            yield from [5 => 10, 10 => 20];
        });

        $this->assertSame(10, $sequence->get(0));
        $this->assertSame(20, $sequence->get(1));
    }

    public function testConstructorReIndexesDuplicateClosureKeys(): void
    {
        $sequence = new Sequence(static function () {
            yield from [10, 20];

            yield from [30, 40];
        });

        $this->assertSequence($sequence, [10, 20, 30, 40]);

        $this->assertSame(30, $sequence->get(2));
    }
}
