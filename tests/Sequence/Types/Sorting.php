<?php

use Liamduckett\Structures\Sequence;

use function PHPStan\Testing\assertType;

$sequence = Sequence::make([3, 1, 2]);

// SortAscending ---

$ascending = $sequence->sortAscending();

assertType('Liamduckett\Structures\Sequence<int>', $ascending);

// SortDescending ---

$descending = $sequence->sortDescending();

assertType('Liamduckett\Structures\Sequence<int>', $descending);
