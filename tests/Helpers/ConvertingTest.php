<?php

namespace Tests\Helpers;

use ArrayIterator;
use ArrayObject;
use IteratorIterator;
use PHPUnit\Framework\TestCase;
use Tests\Concerns\TestsGenerators;

use function Liamduckett\Structures\iterable_resume;
use function Liamduckett\Structures\iterable_to_array;
use function Liamduckett\Structures\iterable_to_generator;
use function Liamduckett\Structures\value;

/**
 * @internal
 *
 * @coversNothing
 */
class ConvertingTest extends TestCase
{
    use TestsGenerators;

    // Value ---

    public function testValueReturnsIterablesUnchanged(): void
    {
        $result = value([1, 2, 3]);

        $this->assertSame([1, 2, 3], $result); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testValueCallsClosures(): void
    {
        $result = value(static fn () => [1, 2, 3]);

        $this->assertSame([1, 2, 3], $result); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testValueAcceptsGenerators(): void
    {
        $result = value((static function () {
            yield 1;

            yield 2;
        })());

        $this->assertSame([1, 2], iterator_to_array($result));
    }

    public function testValueAcceptsGeneratorFunctions(): void
    {
        $result = value(static function () {
            yield 1;

            yield 2;
        });

        $this->assertSame([1, 2], iterator_to_array($result));
    }

    public function testValueAcceptsIterables(): void
    {
        $result = value(new ArrayIterator([1, 2, 3]));

        $this->assertSame([1, 2, 3], iterator_to_array($result));
    }

    public function testValueReturnsPartiallyConsumedGeneratorsUnchanged(): void
    {
        $generator = $this->buildPartiallyConsumedGenerator([1, 2]);

        $result = value($generator);

        $this->assertSame($generator, $result);
    }

    // To Array ---

    public function testToArrayAcceptsArrays(): void
    {
        $result = iterable_to_array([1, 2, 3]);

        $this->assertSame([1, 2, 3], $result);
    }

    public function testToArrayAcceptsIterables(): void
    {
        $result = iterable_to_array(new ArrayIterator([1, 2, 3]));

        $this->assertSame([1, 2, 3], $result);
    }

    public function testToArrayAcceptsGenerators(): void
    {
        $result = iterable_to_array((static function () {
            yield 'a' => 1;

            yield 'b' => 2;
        })());

        $this->assertSame(['a' => 1, 'b' => 2], $result);
    }

    public function testToArrayPreservesKeys(): void
    {
        $result = iterable_to_array(['a' => 1, 'b' => 2]);

        $this->assertSame(['a' => 1, 'b' => 2], $result);
    }

    public function testToArrayReturnsEmptyForEmptyArray(): void
    {
        $result = iterable_to_array([]);

        $this->assertSame([], $result); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testToArrayReturnsEmptyForEmptyGenerator(): void
    {
        $result = iterable_to_array((static function () {
            yield from [];
        })());

        $this->assertSame([], $result); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testToArrayRejectsPartiallyConsumedGenerators(): void
    {
        $this->expectGeneratorAlreadyRunException();

        iterable_to_array($this->buildPartiallyConsumedGenerator([1, 2]));
    }

    // To Generator ---

    public function testToGeneratorAcceptsArrays(): void
    {
        $result = iterable_to_generator([1, 2, 3]);

        $this->assertSame([0 => 1, 1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testToGeneratorAcceptsIterables(): void
    {
        $result = iterable_to_generator(new ArrayIterator([1, 2, 3]));

        $this->assertSame([0 => 1, 1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testToGeneratorAcceptsGenerators(): void
    {
        $input = (static function () {
            yield 1;

            yield 2;

            yield 3;
        })();

        $result = iterable_to_generator($input);

        $this->assertSame([0 => 1, 1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testToGeneratorPreservesKeys(): void
    {
        $result = iterable_to_generator(['a' => 1, 'b' => 2]);

        $this->assertSame(['a' => 1, 'b' => 2], iterator_to_array($result));
    }

    public function testToGeneratorReturnsEmptyForEmptyIterable(): void
    {
        $result = iterable_to_generator([]);

        $this->assertSame([], iterator_to_array($result));
    }

    public function testToGeneratorRejectsPartiallyConsumedGenerators(): void
    {
        $result = iterable_to_generator($this->buildPartiallyConsumedGenerator([1, 2]));

        $this->expectGeneratorAlreadyRunException();

        iterator_to_array($result);
    }

    // Resume ---

    public function testResumeYieldsRemainingItemsOfPartiallyConsumedGenerators(): void
    {
        $result = iterable_resume($this->buildPartiallyConsumedGenerator([1, 2, 3]));

        $this->assertSame([2, 3], iterator_to_array($result, false));
    }

    public function testResumeYieldsAllItemsOfUnstartedGenerators(): void
    {
        $result = iterable_resume((static function () {
            yield 1;

            yield 2;
        })());

        $this->assertSame([1, 2], iterator_to_array($result, false));
    }

    public function testResumeReturnsEmptyForExhaustedGenerators(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;
        })();

        iterator_to_array($generator);

        $this->assertSame([], iterator_to_array(iterable_resume($generator), false));
    }

    public function testResumePreservesKeys(): void
    {
        $result = iterable_resume($this->buildPartiallyConsumedGenerator([
            'foo' => 1,
            'bar' => 2,
            'baz' => 3,
        ]));

        $this->assertSame(['bar' => 2, 'baz' => 3], iterator_to_array($result));
    }

    public function testResumeYieldsItemUnderCursorAgainAfterBreak(): void
    {
        $generator = (static function () {
            yield 1;

            yield 2;

            yield 3;
        })();

        $consumed = [];

        foreach ($generator as $value) {
            $consumed[] = $value;

            if (2 === $value) {
                break;
            }
        }

        $this->assertSame([1, 2], $consumed);
        $this->assertSame([2, 3], iterator_to_array(iterable_resume($generator), false));
    }

    public function testResumeReturnsArraysUnchanged(): void
    {
        $items = [1, 2, 3];

        $this->assertSame($items, iterable_resume($items)); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testResumeReturnsIteratorsUnchanged(): void
    {
        $iterator = new ArrayIterator([1, 2, 3]);

        $iterator->next();

        $this->assertSame($iterator, iterable_resume($iterator));
    }

    public function testResumeLeavesIteratorsThatInitialiseOnRewindIntact(): void
    {
        $iterator = new IteratorIterator($this->buildPartiallyConsumedGenerator([1, 2, 3]));

        $this->assertSame($iterator, iterable_resume($iterator));
    }

    public function testResumeReturnsIteratorAggregatesUnchanged(): void
    {
        $aggregate = new ArrayObject([1, 2, 3]);

        $this->assertSame($aggregate, iterable_resume($aggregate));
    }
}
