<?php

use Liamduckett\Structures\Sequence;

use function PHPStan\Testing\assertType;

// Make ---

$empty = Sequence::make();
$typed = Sequence::make([1, 2, 3]);

assertType('Liamduckett\Structures\Sequence<mixed>', $empty);
assertType('Liamduckett\Structures\Sequence<int>', $typed);
