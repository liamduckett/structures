<?php

namespace Tests\Sequence;

use Liamduckett\Structures\Sequence;
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

    // Contains ---

    public function testContainsReturnsTrueForExistingValue(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $contains = $sequence->contains(2);

        $this->assertTrue($contains);
    }

    public function testContainsReturnsFalseForMissingValue(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $contains = $sequence->contains(99);

        $this->assertFalse($contains);
    }

    public function testContainsUsesStrictComparison(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $contains = $sequence->contains('1'); // @phpstan-ignore argument.type

        $this->assertFalse($contains);
    }

    public function testContainsReturnsTrueForNullValue(): void
    {
        $sequence = Sequence::make([1, null, 3]);

        $contains = $sequence->contains(null);

        $this->assertTrue($contains);
    }

    public function testContainsDistinguishesNullFromOtherFalsyValues(): void
    {
        $sequence = Sequence::make([0, '', false]);

        $contains = $sequence->contains(null); // @phpstan-ignore argument.type

        $this->assertFalse($contains);
    }

    // DoesntContain ---

    public function testDoesntContainReturnsFalseForExistingValue(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $doesntContain = $sequence->doesntContain(2);

        $this->assertFalse($doesntContain);
    }

    public function testDoesntContainReturnsTrueForMissingValue(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $doesntContain = $sequence->doesntContain(99);

        $this->assertTrue($doesntContain);
    }

    public function testDoesntContainReturnsFalseForNullValue(): void
    {
        $sequence = Sequence::make([1, null, 3]);

        $doesntContain = $sequence->doesntContain(null);

        $this->assertFalse($doesntContain);
    }

    // IsEmpty ---

    public function testIsEmptyReturnsTrueForEmptySequence(): void
    {
        $sequence = Sequence::make();

        $isEmpty = $sequence->isEmpty();

        $this->assertTrue($isEmpty);
    }

    public function testIsEmptyReturnsFalseForNonEmptySequence(): void
    {
        $sequence = Sequence::make([1]);

        $isEmpty = $sequence->isEmpty();

        $this->assertFalse($isEmpty);
    }

    // IsNotEmpty ---

    public function testIsNotEmptyReturnsTrueForNonEmptySequence(): void
    {
        $sequence = Sequence::make([1]);

        $isNotEmpty = $sequence->isNotEmpty();

        $this->assertTrue($isNotEmpty);
    }

    public function testIsNotEmptyReturnsFalseForEmptySequence(): void
    {
        $sequence = Sequence::make();

        $isNotEmpty = $sequence->isNotEmpty();

        $this->assertFalse($isNotEmpty);
    }
}
