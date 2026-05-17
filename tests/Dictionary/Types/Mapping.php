<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['foo' => 1]);

// Map ---

$mapped = $dictionary->map(static fn (int $value) => $value * 2);

assertType('Liamduckett\Structures\Dictionary<int>', $mapped);

$mappedToBool = $dictionary->map(static fn (int $value) => $value > 0);

assertType('Liamduckett\Structures\Dictionary<bool>', $mappedToBool);
