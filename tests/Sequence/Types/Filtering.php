<?php

use Liamduckett\Structures\Sequence;

use function PHPStan\Testing\assertType;

$sequence = Sequence::make([1, 2, 3]);

// Filter ---

$filtered = $sequence->filter(static fn (int $value) => $value > 1);

assertType('Liamduckett\Structures\Sequence<int>', $filtered);

// Search ---

$searched = $sequence->search(1);

assertType('Liamduckett\Structures\Sequence<int>', $searched);
