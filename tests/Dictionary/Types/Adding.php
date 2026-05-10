<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['foo' => 1]);

// Set ---

$withSet = $dictionary->set('bar', 2);

assertType('Liamduckett\Structures\Dictionary<int>', $withSet);

// Merge ---

$withMerge = $dictionary->merge(['bar' => 2]);

assertType('Liamduckett\Structures\Dictionary<int>', $withMerge);
