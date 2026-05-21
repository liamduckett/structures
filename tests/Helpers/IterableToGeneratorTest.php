<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\iterable_to_generator;

/**
 * @internal
 *
 * @coversNothing
 */
class IterableToGeneratorTest extends TestCase
{
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
}
