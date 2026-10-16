<?php

namespace Tests\Dictionary\Types;

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['foo' => 1]);

// Remove ---

$removed = $dictionary->remove('foo');

assertType('Liamduckett\Structures\Dictionary<int>', $removed);

$removedEmptyKey = $dictionary->remove('');

assertType('Liamduckett\Structures\Dictionary<int>', $removedEmptyKey);
