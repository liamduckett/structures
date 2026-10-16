<?php

namespace Tests\Helpers\Types;

use function Liamduckett\Structures\iterable_filter;
use function Liamduckett\Structures\iterable_search;
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

// Search ---

$result = iterable_search([10, 20, 30], 20);

assertType('Generator<int, 0|1|2, mixed, void>', $result);

$result = iterable_search(['a' => 1, 'b' => 2], 1);

assertType("Generator<int, 'a'|'b', mixed, void>", $result);

$result = iterable_search($intArray, 1);

assertType('Generator<int, int, mixed, void>', $result);

$result = iterable_search($notEmptyStringArray, 1);

assertType('Generator<int, non-empty-string, mixed, void>', $result);
