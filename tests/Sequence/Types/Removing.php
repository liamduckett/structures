<?php

use Liamduckett\Structures\Sequence;

use function PHPStan\Testing\assertType;

$sequence = Sequence::make([1, 2, 3]);

// Slice ---

$sliced = $sequence->slice(0, 1);

assertType('Liamduckett\Structures\Sequence<int>', $sliced);
