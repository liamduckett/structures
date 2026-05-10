<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['foo' => 1]);

// Get ---

$existing = $dictionary->get('foo');
$missing = $dictionary->get('bar');

assertType('int', $existing);
assertType('int', $missing);

// Keys ---

$keys = $dictionary->keys();

assertType('iterable<non-empty-string>', $keys);

// Items ---

$items = $dictionary->items();

assertType('iterable<non-empty-string, int>', $items);

// Values ---

$values = $dictionary->values();

assertType('iterable<int>', $values);
