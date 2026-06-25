<?php

use function Liamduckett\Structures\iterable_slice;
use function PHPStan\Testing\assertType;

// Slice ---

$result = iterable_slice([1, 2, 3, 4, 5], 1, 2);

assertType('Generator<0|1|2|3|4, int, mixed, void>', $result);

$result = iterable_slice(['a' => 1, 'b' => 2], 1);

assertType("Generator<'a'|'b', int, mixed, void>", $result);

/** @var array<int, int> $intArray */
$intArray = [];
$result = iterable_slice($intArray, 0);

assertType('Generator<int, int, mixed, void>', $result);

/** @var array<non-empty-string, int> $notEmptyStringArray */
$notEmptyStringArray = [];
$result = iterable_slice($notEmptyStringArray, 0);

assertType('Generator<non-empty-string, int, mixed, void>', $result);
