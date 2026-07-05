<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['foo' => 1]);

// Get ---

$existing = $dictionary->get('foo');
$missing = $dictionary->get('bar');
$emptyKey = $dictionary->get('');

assertType('int', $existing);
assertType('int', $missing);
assertType('int', $emptyKey);

// Keys ---

$keys = $dictionary->keys();

assertType('Liamduckett\Structures\Sequence<non-empty-string>', $keys);

// Values ---

$values = $dictionary->values();

assertType('Liamduckett\Structures\Sequence<int>', $values);
