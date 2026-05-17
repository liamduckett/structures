<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['foo' => 1]);

// Filter ---

$filtered = $dictionary->filter(static fn (int $value) => $value > 0);

assertType('Liamduckett\Structures\Dictionary<int>', $filtered);

// Search ---

$keys = $dictionary->search(1);

assertType('Liamduckett\Structures\Sequence<non-empty-string>', $keys);
