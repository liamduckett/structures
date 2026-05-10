<?php

use Liamduckett\Structures\Sequence;

use function PHPStan\Testing\assertType;

$sequence = Sequence::make([1, 2, 3]);

// Contains ---

$contains = $sequence->contains(1);

assertType('bool', $contains);

// DoesntContain ---

$doesntContain = $sequence->doesntContain(99);

assertType('bool', $doesntContain);

// IsEmpty ---

$isEmpty = $sequence->isEmpty();

assertType('bool', $isEmpty);

// IsNotEmpty ---

$isNotEmpty = $sequence->isNotEmpty();

assertType('bool', $isNotEmpty);
