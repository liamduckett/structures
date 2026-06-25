<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

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
    // Value ---

    public function testValueReturnsAnIterable(): void
    {
        /** @var list<int> $array */
        $array = [1, 2, 3];

        $result = value($array);

        $this->assertSame([1, 2, 3], $result);
    }

    public function testValueCallsAClosure(): void
    {
        /** @var list<int> $array */
        $array = [1, 2, 3];

        $result = value(static fn () => $array);

        $this->assertSame([1, 2, 3], $result);
    }

    public function testValueAcceptsAGenerator(): void
    {
        $result = value((static function () {
            yield 1;

            yield 2;
        })());

        $this->assertSame([1, 2], iterator_to_array($result));
    }

    public function testValueAcceptsAGeneratorFunction(): void
    {
        $result = value(static function () {
            yield 1;

            yield 2;
        });

        $this->assertSame([1, 2], iterator_to_array($result));
    }

    public function testValueAcceptsAnIterable(): void
    {
        $result = value(new ArrayIterator([1, 2, 3]));

        $this->assertSame([1, 2, 3], iterator_to_array($result));
    }

    // To Array ---

    public function testConvertsAnArray(): void
    {
        $result = iterable_to_array([1, 2, 3]);

        $this->assertSame([1, 2, 3], $result);
    }

    public function testConvertsAnIterable(): void
    {
        $result = iterable_to_array(new ArrayIterator([1, 2, 3]));

        $this->assertSame([1, 2, 3], $result);
    }

    public function testConvertsAGenerator(): void
    {
        $result = iterable_to_array((static function () {
            yield 'a' => 1;

            yield 'b' => 2;
        })());

        $this->assertSame(['a' => 1, 'b' => 2], $result);
    }

    public function testPreservesKeys(): void
    {
        $result = iterable_to_array(['a' => 1, 'b' => 2]);

        $this->assertSame(['a' => 1, 'b' => 2], $result);
    }

    public function testConvertsAnEmptyArray(): void
    {
        $result = iterable_to_array([]);

        $this->assertSame([], $result); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testConvertsAnEmptyGenerator(): void
    {
        $result = iterable_to_array((static function () {
            yield from [];
        })());

        $this->assertSame([], $result); // @phpstan-ignore method.alreadyNarrowedType
    }

    // To Generator ---

    public function testIterableToGeneratorFromArray(): void
    {
        $result = iterable_to_generator([1, 2, 3]);

        $this->assertSame([0 => 1, 1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testIterableToGeneratorFromIterable(): void
    {
        $result = iterable_to_generator(new ArrayIterator([1, 2, 3]));

        $this->assertSame([0 => 1, 1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testIterableToGeneratorFromGenerator(): void
    {
        $input = (static function () {
            yield 1;

            yield 2;

            yield 3;
        })();

        $result = iterable_to_generator($input);

        $this->assertSame([0 => 1, 1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testKeysArePreserved(): void
    {
        $result = iterable_to_generator(['a' => 1, 'b' => 2]);

        $this->assertSame(['a' => 1, 'b' => 2], iterator_to_array($result));
    }

    public function testConvertsAnEmptyIterable(): void
    {
        $result = iterable_to_generator([]);

        $this->assertSame([], iterator_to_array($result));
    }
}
