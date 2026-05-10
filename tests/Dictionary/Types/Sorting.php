<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['banana' => 2, 'apple' => 1]);

// SortKeyAscending ---

$sorted = $dictionary->sortKeyAscending();

assertType('Liamduckett\Structures\Dictionary<int>', $sorted);
