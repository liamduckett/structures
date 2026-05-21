<?php

use function Liamduckett\Structures\iterable_map;
use function PHPStan\Testing\assertType;

$result = iterable_map([1, 2, 3], static fn (int $value) => $value * 2);

assertType('Generator<0|1|2, int, mixed, void>', $result);

$result = iterable_map(['a' => 1, 'b' => 2], static fn (int $value) => $value);

assertType("Generator<'a'|'b', 1|2, mixed, void>", $result);

/** @var array<int, int> $intArray */
$intArray = [];
$result = iterable_map($intArray, static fn (int $value) => $value * 2);

assertType('Generator<int, int, mixed, void>', $result);

/** @var array<non-empty-string, int> $notEmptyStringArray */
$notEmptyStringArray = [];
$result = iterable_map($notEmptyStringArray, static fn (int $value) => $value);

assertType('Generator<non-empty-string, int, mixed, void>', $result);
