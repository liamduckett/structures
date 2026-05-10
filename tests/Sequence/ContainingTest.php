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

    public function testContainsReturnsTrueForAnExistingValue(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $contains = $sequence->contains(2);

        $this->assertTrue($contains);
    }

    public function testContainsReturnsFalseForAMissingValue(): void
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

    // DoesntContain ---

    public function testDoesntContainReturnsFalseForAnExistingValue(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $doesntContain = $sequence->doesntContain(2);

        $this->assertFalse($doesntContain);
    }

    public function testDoesntContainReturnsTrueForAMissingValue(): void
    {
        $sequence = Sequence::make([1, 2, 3]);

        $doesntContain = $sequence->doesntContain(99);

        $this->assertTrue($doesntContain);
    }

    // IsEmpty ---

    public function testIsEmptyReturnsTrueForAnEmptySequence(): void
    {
        $sequence = Sequence::make();

        $isEmpty = $sequence->isEmpty();

        $this->assertTrue($isEmpty);
    }

    public function testIsEmptyReturnsFalseForANonEmptySequence(): void
    {
        $sequence = Sequence::make([1]);

        $isEmpty = $sequence->isEmpty();

        $this->assertFalse($isEmpty);
    }

    // IsNotEmpty ---

    public function testIsNotEmptyReturnsTrueForANonEmptySequence(): void
    {
        $sequence = Sequence::make([1]);

        $isNotEmpty = $sequence->isNotEmpty();

        $this->assertTrue($isNotEmpty);
    }

    public function testIsNotEmptyReturnsFalseForAnEmptySequence(): void
    {
        $sequence = Sequence::make();

        $isNotEmpty = $sequence->isNotEmpty();

        $this->assertFalse($isNotEmpty);
    }
}
