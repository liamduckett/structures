<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['foo' => 1]);

// Set ---

$set = $dictionary->set('bar', 2);

assertType('Liamduckett\Structures\Dictionary<int>', $set);

$setWithEmptyKey = $dictionary->set('', 2); // @phpstan-ignore argument.type

assertType('Liamduckett\Structures\Dictionary<int>', $setWithEmptyKey);
