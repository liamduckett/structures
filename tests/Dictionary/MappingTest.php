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
class MappingTest extends TestCase
{
    use TestsStructures;

    // Map ---

    public function testMapTransformsValues(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $mapped = $dictionary->map(fn (int $value) => $value * 2);

        $this->assertDictionary($mapped, [
            'foo' => 2,
            'bar' => 4,
        ]);
    }

    public function testMapPassesKeyToCallable(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $mapped = $dictionary->map(fn (int $value, string $key) => $key);

        $this->assertDictionary($mapped, [
            'foo' => 'foo',
            'bar' => 'bar',
        ]);
    }

    public function testMapPreservesKeys(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $mapped = $dictionary->map(fn (int $value) => $value * 10);
        $keys = $mapped->keys();

        $this->assertSequence($keys, ['foo', 'bar']);
    }

    public function testMapIsImmutable(): void
    {
        $original = Dictionary::make([
            'foo' => 1,
            'bar' => 2,
        ]);

        $mapped = $original->map(fn (int $value) => $value * 2);

        $this->assertNotSame($original, $mapped);

        $this->assertDictionary($original, [
            'foo' => 1,
            'bar' => 2,
        ]);
    }
}
