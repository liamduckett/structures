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

    public function testContainsKeyUsesStrictComparison(): void
    {
        $dictionary = Dictionary::make([1 => 'foo']); // @phpstan-ignore argument.type

        $containsKey = $dictionary->containsKey('1');

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

    public function testContainsValueReturnsTrueForNullValue(): void
    {
        $dictionary = Dictionary::make(['foo' => null]);

        $containsValue = $dictionary->containsValue(null);

        $this->assertTrue($containsValue);
    }

    public function testContainsValueDistinguishesNullFromOtherFalsyValues(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 0,
            'bar' => '',
            'baz' => false,
        ]);

        $containsValue = $dictionary->containsValue(null); // @phpstan-ignore argument.type

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

    public function testDoesntContainKeyUsesStrictComparison(): void
    {
        $dictionary = Dictionary::make([1 => 'foo']); // @phpstan-ignore argument.type

        $doesntContainKey = $dictionary->doesntContainKey('1');

        $this->assertTrue($doesntContainKey);
    }

    public function testDoesntContainKeyReturnsFalseForKeyHoldingNull(): void
    {
        $dictionary = Dictionary::make(['foo' => null]);

        $doesntContainKey = $dictionary->doesntContainKey('foo');

        $this->assertFalse($doesntContainKey);
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

    public function testDoesntContainValueUsesStrictComparison(): void
    {
        $dictionary = Dictionary::make(['foo' => 1]);

        $doesntContainValue = $dictionary->doesntContainValue('1'); // @phpstan-ignore argument.type

        $this->assertTrue($doesntContainValue);
    }

    public function testDoesntContainValueReturnsFalseForNullValue(): void
    {
        $dictionary = Dictionary::make(['foo' => null]);

        $doesntContainValue = $dictionary->doesntContainValue(null);

        $this->assertFalse($doesntContainValue);
    }

    public function testDoesntContainValueDistinguishesNullFromOtherFalsyValues(): void
    {
        $dictionary = Dictionary::make([
            'foo' => 0,
            'bar' => '',
            'baz' => false,
        ]);

        $doesntContainValue = $dictionary->doesntContainValue(null); // @phpstan-ignore argument.type

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
