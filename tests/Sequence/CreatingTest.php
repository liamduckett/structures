<?php

namespace Tests\Sequence;

use Liamduckett\Structures\Sequence;
use PHPUnit\Framework\TestCase;
use Tests\Concerns\TestsGenerators;
use Tests\Concerns\TestsStructures;

/**
 * @internal
 *
 * @coversNothing
 */
class CreatingTest extends TestCase
{
    use TestsGenerators;
    use TestsStructures;

    public function testMakeAcceptsArrays(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $this->assertSequence($sequence, [1, 2, 3]);
    }

    public function testMakeAcceptsIterables(): void
    {
        $sequence = Sequence::make($this->buildTypedIterator([1, 2, 3]));

        $this->assertSequence($sequence, [1, 2, 3]);
    }

    public function testMakeAcceptsGenerators(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;
        })();

        $sequence = Sequence::make($generator);

        $this->assertSequence($sequence, [1, 2]);
    }

    public function testMakeRejectsPartiallyConsumedGenerators(): void
    {
        $sequence = Sequence::make($this->buildPartiallyConsumedGenerator([1, 2]));

        $this->expectGeneratorAlreadyRunException();

        $sequence->array();
    }

    public function testMakeDefaultsToEmpty(): void
    {
        $this->assertSequence(Sequence::make(), []);
    }

    public function testMakeReindexesKeys(): void
    {
        $sequence = Sequence::make([
            5 => 1,
            10 => 2,
        ]);

        $this->assertSequence($sequence, [1, 2]);
    }

    public function testConstructorReindexesClosureKeys(): void
    {
        $sequence = new Sequence(static function () {
            yield from [5 => 10, 10 => 20];
        });

        $this->assertSame(10, $sequence->get(0));
        $this->assertSame(20, $sequence->get(1));
    }

    public function testConstructorReindexesDuplicateClosureKeys(): void
    {
        $sequence = new Sequence(static function () {
            yield from [10, 20];

            yield from [30, 40];
        });

        $this->assertSequence($sequence, [10, 20, 30, 40]);

        $this->assertSame(30, $sequence->get(2));
    }

    public function testMakeCanBeIteratedTwice(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $this->assertIteratesTwice($sequence, [1, 2, 3]);
    }

    public function testConstructorCanBeIteratedTwice(): void
    {
        $sequence = new Sequence(static function () {
            yield from [1, 2, 3];
        });

        $this->assertIteratesTwice($sequence, [1, 2, 3]);
    }

    public function testMakeRejectsGeneratorsOnSecondIteration(): void
    {
        $sequence = Sequence::make((static function () {
            yield 1;

            yield 2;
        })());

        $this->assertSequence($sequence, [1, 2]);

        $this->expectGeneratorAlreadyClosedException();

        $sequence->array();
    }
}
