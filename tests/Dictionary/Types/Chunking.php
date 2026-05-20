<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['a' => 1, 'b' => 2]);

// Chunk ---

$chunks = $dictionary->chunk(2);

assertType('Liamduckett\Structures\Sequence<Liamduckett\Structures\Dictionary<int>>', $chunks);
