<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\value;

/**
 * @internal
 *
 * @coversNothing
 */
class ValueTest extends TestCase
{
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

        $result = value(fn () => $array);

        $this->assertSame([1, 2, 3], $result);
    }

    public function testValueAcceptsAGenerator(): void
    {
        $result = value((function () {
            yield 1;

            yield 2;
        })());

        $this->assertSame([1, 2], iterator_to_array($result));
    }

    public function testValueAcceptsAGeneratorFunction(): void
    {
        $result = value(function () {
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
}
