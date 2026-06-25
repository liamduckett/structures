<?php

use function Liamduckett\Structures\iterable_merge;
use function PHPStan\Testing\assertType;

// Merge ---

$result = iterable_merge([1, 2], [3, 4]);

assertType('Generator<0|1, int, mixed, void>', $result);

$result = iterable_merge(['a' => 'foo'], ['b' => 'bar']);

assertType("Generator<'a'|'b', string, mixed, void>", $result);

/** @var array<int, int> $intArray */
$intArray = [];
$result = iterable_merge($intArray, $intArray);

assertType('Generator<int, int, mixed, void>', $result);

/** @var array<non-empty-string, string> $notEmptyStringArray */
$notEmptyStringArray = [];
$result = iterable_merge($notEmptyStringArray, $notEmptyStringArray);

assertType('Generator<non-empty-string, string, mixed, void>', $result);
