<?php

use function Liamduckett\Structures\iterable_keys;
use function PHPStan\Testing\assertType;

$result = iterable_keys([1, 2, 3]);

assertType('Generator<int, 0|1|2, mixed, void>', $result);

$result = iterable_keys(['a' => 1, 'b' => 2]);

assertType("Generator<int, 'a'|'b', mixed, void>", $result);

/** @var array<int, int> $intArray */
$intArray = [];
$result = iterable_keys($intArray);

assertType('Generator<int, int, mixed, void>', $result);

/** @var array<non-empty-string, int> $notEmptyStringArray */
$notEmptyStringArray = [];
$result = iterable_keys($notEmptyStringArray);

assertType('Generator<int, non-empty-string, mixed, void>', $result);
