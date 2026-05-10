<?php

use Liamduckett\Structures\Sequence;

use function PHPStan\Testing\assertType;

$sequence = Sequence::make([1, 2, 3]);

// Pop ---

$popped = $sequence->pop();

assertType('Liamduckett\Structures\Sequence<int>', $popped);

// Shift ---

$shifted = $sequence->shift();

assertType('Liamduckett\Structures\Sequence<int>', $shifted);
