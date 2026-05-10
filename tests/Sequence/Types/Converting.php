<?php

use Liamduckett\Structures\Sequence;

use function PHPStan\Testing\assertType;

$sequence = Sequence::make([1, 2, 3]);

// Array ---

$array = $sequence->array();

assertType('list<int>', $array);

// GetIterator ---

$iterator = $sequence->getIterator();

assertType('Traversable<int, int>', $iterator);

// Iterator ---

$traversable = $sequence->iterator();

assertType('Traversable<int, int>', $traversable);
