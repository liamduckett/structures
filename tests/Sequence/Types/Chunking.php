<?php

use Liamduckett\Structures\Sequence;

use function PHPStan\Testing\assertType;

$sequence = Sequence::make([1, 2, 3]);

// Chunk ---

$chunks = $sequence->chunk(2);

assertType('Liamduckett\Structures\Sequence<Liamduckett\Structures\Sequence<int>>', $chunks);
