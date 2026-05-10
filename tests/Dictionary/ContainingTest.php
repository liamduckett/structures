<?php

namespace Tests\Dictionary;

use Liamduckett\Structures\Dictionary;
use PHPUnit\Framework\TestCase;
use Tests\Dictionary\Concerns\TestsDictionaries;

/**
 * @internal
 *
 * @coversNothing
 */
class ContainingTest extends TestCase
{
    use TestsDictionaries;

    // ContainsKey ---

    public function testContainsKeyReturnsTrueForAnExistingKey(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $containsKey = $dictionary->containsKey('foo');

        $this->assertTrue($containsKey);
    }

    public function testContainsKeyReturnsFalseForAMissingKey(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $containsKey = $dictionary->containsKey('bar');

        $this->assertFalse($containsKey);
    }

    public function testContainsKeyReturnsTrueForAKeyHoldingNull(): void
    {
        $dictionary = Dictionary::make(['foo' => null]);

        $containsKey = $dictionary->containsKey('foo');

        $this->assertTrue($containsKey);
    }

    // ContainsValue ---

    public function testContainsValueReturnsTrueForAnExistingValue(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $containsValue = $dictionary->containsValue(1);

        $this->assertTrue($containsValue);
    }

    public function testContainsValueReturnsFalseForAMissingValue(): void
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

    public function testDoesntContainKeyReturnsFalseForAnExistingKey(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $doesntContainKey = $dictionary->doesntContainKey('foo');

        $this->assertFalse($doesntContainKey);
    }

    public function testDoesntContainKeyReturnsTrueForAMissingKey(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $doesntContainKey = $dictionary->doesntContainKey('bar');

        $this->assertTrue($doesntContainKey);
    }

    // DoesntContainValue ---

    public function testDoesntContainValueReturnsFalseForAnExistingValue(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $doesntContainValue = $dictionary->doesntContainValue(1);

        $this->assertFalse($doesntContainValue);
    }

    public function testDoesntContainValueReturnsTrueForAMissingValue(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $doesntContainValue = $dictionary->doesntContainValue(99);

        $this->assertTrue($doesntContainValue);
    }

    // IsEmpty ---

    public function testIsEmptyReturnsTrueForAnEmptyDictionary(): void
    {
        $dictionary = Dictionary::make();

        $isEmpty = $dictionary->isEmpty();

        $this->assertTrue($isEmpty);
    }

    public function testIsEmptyReturnsFalseForANonEmptyDictionary(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $isEmpty = $dictionary->isEmpty();

        $this->assertFalse($isEmpty);
    }

    // IsNotEmpty ---

    public function testIsNotEmptyReturnsTrueForANonEmptyDictionary(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $isNotEmpty = $dictionary->isNotEmpty();

        $this->assertTrue($isNotEmpty);
    }

    public function testIsNotEmptyReturnsFalseForAnEmptyDictionary(): void
    {
        $dictionary = Dictionary::make();

        $isNotEmpty = $dictionary->isNotEmpty();

        $this->assertFalse($isNotEmpty);
    }
}
