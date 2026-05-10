<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['foo' => 1]);

// Map ---

$mapped = $dictionary->map(fn (int $value) => $value * 2);

assertType('Liamduckett\Structures\Dictionary<int>', $mapped);
