<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

// Construct ---

$empty = new Dictionary(['' => 1]); // @phpstan-ignore argument.type

assertType('Liamduckett\Structures\Dictionary<int>', $empty);

// Make ---

$empty = Dictionary::make();

assertType('Liamduckett\Structures\Dictionary<mixed>', $empty);

$typed = Dictionary::make(['foo' => 1]);

assertType('Liamduckett\Structures\Dictionary<int>', $typed);

$madeWithEmptyKey = Dictionary::make(['' => 1]); // @phpstan-ignore argument.type

assertType('Liamduckett\Structures\Dictionary<int>', $madeWithEmptyKey);
