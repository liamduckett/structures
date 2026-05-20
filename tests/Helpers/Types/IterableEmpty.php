<?php

use function Liamduckett\Structures\iterable_empty;
use function PHPStan\Testing\assertType;

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
