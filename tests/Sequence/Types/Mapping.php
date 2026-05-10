<?php

use Liamduckett\Structures\Sequence;

use function PHPStan\Testing\assertType;

$sequence = Sequence::make([1, 2, 3]);

// Map ---

$mapped = $sequence->map(fn (int $value) => $value * 2);

assertType('Liamduckett\Structures\Sequence<int>', $mapped);

$mappedToBool = $sequence->map(fn (int $value) => $value > 0);

assertType('Liamduckett\Structures\Sequence<bool>', $mappedToBool);
