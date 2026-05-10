<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['foo' => 1]);

// Array ---

$array = $dictionary->array();

assertType('array<non-empty-string, int>', $array);

// GetIterator ---

$iterator = $dictionary->getIterator();

assertType('Traversable<non-empty-string, int>', $iterator);

// Iterator ---

$traversable = $dictionary->iterator();

assertType('Traversable<non-empty-string, int>', $traversable);
