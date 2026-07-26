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
class ContainingTest extends TestCase
{
    use TestsStructures;

    // ContainsKey ---

    public function testContainsKeyReturnsTrueForExistingKey(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $containsKey = $dictionary->containsKey('foo');

        $this->assertTrue($containsKey);
    }

    public function testContainsKeyReturnsFalseForMissingKey(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $containsKey = $dictionary->containsKey('bar');

        $this->assertFalse($containsKey);
    }

    public function testContainsKeyReturnsTrueForKeyHoldingNull(): void
    {
        $dictionary = Dictionary::make(['foo' => null]);

        $containsKey = $dictionary->containsKey('foo');

        $this->assertTrue($containsKey);
    }

    // ContainsValue ---

    public function testContainsValueReturnsTrueForExistingValue(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $containsValue = $dictionary->containsValue(1);

        $this->assertTrue($containsValue);
    }

    public function testContainsValueReturnsFalseForMissingValue(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $containsValue = $dictionary->containsValue(99);

        $this->assertFalse($containsValue);
    }

    public function testContainsValueUsesStrictComparison(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $containsValue = $dictionary->containsValue('1'); // @phpstan-ignore argument.type

        $this->assertFalse($containsValue);
    }

    // DoesntContainKey ---

    public function testDoesntContainKeyReturnsFalseForExistingKey(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $doesntContainKey = $dictionary->doesntContainKey('foo');

        $this->assertFalse($doesntContainKey);
    }

    public function testDoesntContainKeyReturnsTrueForMissingKey(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $doesntContainKey = $dictionary->doesntContainKey('bar');

        $this->assertTrue($doesntContainKey);
    }

    // DoesntContainValue ---

    public function testDoesntContainValueReturnsFalseForExistingValue(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $doesntContainValue = $dictionary->doesntContainValue(1);

        $this->assertFalse($doesntContainValue);
    }

    public function testDoesntContainValueReturnsTrueForMissingValue(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $doesntContainValue = $dictionary->doesntContainValue(99);

        $this->assertTrue($doesntContainValue);
    }

    // IsEmpty ---

    public function testIsEmptyReturnsTrueForEmptyDictionary(): void
    {
        $dictionary = Dictionary::make();

        $isEmpty = $dictionary->isEmpty();

        $this->assertTrue($isEmpty);
    }

    public function testIsEmptyReturnsFalseForNonEmptyDictionary(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $isEmpty = $dictionary->isEmpty();

        $this->assertFalse($isEmpty);
    }

    // IsNotEmpty ---

    public function testIsNotEmptyReturnsTrueForNonEmptyDictionary(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $isNotEmpty = $dictionary->isNotEmpty();

        $this->assertTrue($isNotEmpty);
    }

    public function testIsNotEmptyReturnsFalseForEmptyDictionary(): void
    {
        $dictionary = Dictionary::make();

        $isNotEmpty = $dictionary->isNotEmpty();

        $this->assertFalse($isNotEmpty);
    }
}
