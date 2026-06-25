<?php

use function Liamduckett\Structures\iterable_contains;
use function Liamduckett\Structures\iterable_contains_key;
use function Liamduckett\Structures\iterable_empty;
use function PHPStan\Testing\assertType;

// Contains ---

$result = iterable_contains([1, 2, 3], 2);

assertType('bool', $result);

$result = iterable_contains(['a' => 'foo', 'b' => 'bar'], 'foo');

assertType('bool', $result);

// Contains Key ---

assertType('bool', iterable_contains_key(['a' => 1, 'b' => 2], 'a'));

assertType('bool', iterable_contains_key([1, 2, 3], 0));

// Empty ---

assertType('false', iterable_empty([1, 2, 3]));

assertType('true', iterable_empty([]));

assertType('bool', iterable_empty(new ArrayIterator([1, 2, 3])));

/** @var list<int> $list */
$list = [];
assertType('bool', iterable_empty($list));

/** @var non-empty-list<int> $nonEmptyList */
$nonEmptyList = [];
assertType('false', iterable_empty($nonEmptyList));

/** @var array<int> $array */
$array = [];
assertType('bool', iterable_empty($array));

/** @var non-empty-array<int> $nonEmptyArray */
$nonEmptyArray = [];
assertType('false', iterable_empty($nonEmptyArray));
