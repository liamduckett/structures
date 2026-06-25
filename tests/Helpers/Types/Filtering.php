<?php

use function Liamduckett\Structures\iterable_filter;
use function PHPStan\Testing\assertType;

// Filter ---

$result = iterable_filter([1, 2, 3], static fn (int $value) => $value > 1);

assertType('Generator<0|1|2, int, mixed, void>', $result);

$result = iterable_filter(['a' => 1, 'b' => 2], static fn (int $value, string $key) => 'b' !== $key);

assertType("Generator<'a'|'b', int, mixed, void>", $result);

/** @var array<int, int> $intArray */
$intArray = [];
$result = iterable_filter($intArray, static fn (int $value) => $value > 1);

assertType('Generator<int, int, mixed, void>', $result);

/** @var array<non-empty-string, int> $notEmptyStringArray */
$notEmptyStringArray = [];
$result = iterable_filter($notEmptyStringArray, static fn (int $value) => true);

assertType('Generator<non-empty-string, int, mixed, void>', $result);
