<?php

use Liamduckett\Structures\Dictionary;

use function PHPStan\Testing\assertType;

$dictionary = Dictionary::make(['foo' => 1]);

// ContainsKey ---

$containsKey = $dictionary->containsKey('foo');

assertType('bool', $containsKey);

$containsKeyEmpty = $dictionary->containsKey('');

assertType('false', $containsKeyEmpty);

// ContainsValue ---

$containsValue = $dictionary->containsValue(1);

assertType('bool', $containsValue);

// DoesntContainKey ---

$doesntContainKey = $dictionary->doesntContainKey('foo');

assertType('bool', $doesntContainKey);

$doesntContainKeyEmpty = $dictionary->doesntContainKey('');

assertType('true', $doesntContainKeyEmpty);

// DoesntContainValue ---

$doesntContainValue = $dictionary->doesntContainValue(1);

assertType('bool', $doesntContainValue);

// IsEmpty ---

$isEmpty = $dictionary->isEmpty();

assertType('bool', $isEmpty);

// IsNotEmpty ---

$isNotEmpty = $dictionary->isNotEmpty();

assertType('bool', $isNotEmpty);
