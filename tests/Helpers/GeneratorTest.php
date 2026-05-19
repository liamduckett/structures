<?php

namespace Tests\Helpers;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

use function Liamduckett\Structures\generator;

/**
 * @internal
 *
 * @coversNothing
 */
class GeneratorTest extends TestCase
{
    public function testGeneratorFromArray(): void
    {
        $result = generator([1, 2, 3]);

        $this->assertSame([0 => 1, 1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testGeneratorFromIterable(): void
    {
        $result = generator(new ArrayIterator([1, 2, 3]));

        $this->assertSame([0 => 1, 1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testGeneratorFromGenerator(): void
    {
        $input = (static function () {
            yield 1;

            yield 2;

            yield 3;
        })();

        $result = generator($input);

        $this->assertSame([0 => 1, 1 => 2, 2 => 3], iterator_to_array($result));
    }

    public function testKeysArePreserved(): void
    {
        $result = generator(['a' => 1, 'b' => 2]);

        $this->assertSame(['a' => 1, 'b' => 2], iterator_to_array($result));
    }
}
