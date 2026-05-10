<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

// Make ---

$empty = Dictionary::make();
$typed = Dictionary::make(['foo' => 1]);

assertType('Liamduckett\Structures\Dictionary<mixed>', $empty);
assertType('Liamduckett\Structures\Dictionary<int>', $typed);
