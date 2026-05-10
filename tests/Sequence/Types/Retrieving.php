<?php

use Liamduckett\Structures\Sequence;

use function PHPStan\Testing\assertType;

$sequence = Sequence::make([1, 2, 3]);

// Get ---

$item = $sequence->get(0);

assertType('int', $item);

// First ---

$first = $sequence->first();

assertType('int', $first);

// Last ---

$last = $sequence->last();

assertType('int', $last);

// Count ---

$count = $sequence->count();

assertType('int<0, max>', $count);
