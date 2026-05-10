<?php

namespace Tests\Dictionary;

use Liamduckett\Structures\Dictionary;
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
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $this->assertDictionary($dictionary, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    public function testMakeWithAnIterable(): void
    {
        $dictionary = Dictionary::make($this->buildTypedIterator([
            'foo' => 1,
            'bar' => 2,
        ]));

        $this->assertDictionary($dictionary, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    public function testMakeWithAGenerator(): void
    {
        $generator = (function () {
            yield 'foo' => 1;

            yield 'bar' => 2;
        })();

        $dictionary = Dictionary::make($generator);

        $this->assertDictionary($dictionary, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }

    public function testMakeDefaultsToEmpty(): void
    {
        $this->assertDictionary(Dictionary::make(), []);
    }
}
