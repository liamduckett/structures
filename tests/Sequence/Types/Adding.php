<?php

use Liamduckett\Structures\Sequence;

use function PHPStan\Testing\assertType;

$sequence = Sequence::make([1, 2, 3]);

// Push ---

$pushed = $sequence->push(4);

assertType('Liamduckett\Structures\Sequence<int>', $pushed);

// Prepend ---

$prepended = $sequence->prepend(0);

assertType('Liamduckett\Structures\Sequence<int>', $prepended);

// Merge ---

$merged = $sequence->merge([4, 5]);

assertType('Liamduckett\Structures\Sequence<int>', $merged);
