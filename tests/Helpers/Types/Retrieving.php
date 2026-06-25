<?php

use ArrayIterator;

use function Liamduckett\Structures\iterable_count;
use function Liamduckett\Structures\iterable_first;
use function Liamduckett\Structures\iterable_get;
use function Liamduckett\Structures\iterable_keys;
use function Liamduckett\Structures\iterable_last;
use function Liamduckett\Structures\iterable_values;
use function PHPStan\Testing\assertType;

// Values ---

$result = iterable_values([1, 2, 3]);

assertType('Generator<int, int, mixed, void>', $result);

$result = iterable_values(['a' => 'foo', 'b' => 'bar']);

assertType('Generator<int, string, mixed, void>', $result);

// Keys ---

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

// Count ---

$result = iterable_count([1, 2, 3]);

assertType('int<0, max>', $result);

$result = iterable_count(new ArrayIterator([1, 2, 3]));

assertType('int<0, max>', $result);

// Get ---

$result = iterable_get([1, 2, 3], 0);

assertType('1|2|3', $result);

$result = iterable_get(['foo', 'bar', 'baz'], 0);

assertType("'bar'|'baz'|'foo'", $result);

$result = iterable_get(['a' => 1, 'b' => 2], 'b');

assertType('1|2', $result);

// First ---

$result = iterable_first([1, 2, 3]);

assertType('1|2|3', $result);

$result = iterable_first(['foo', 'bar', 'baz']);

assertType("'bar'|'baz'|'foo'", $result);

// Last ---

$result = iterable_last([1, 2, 3]);

assertType('1|2|3', $result);

$result = iterable_last(['foo', 'bar', 'baz']);

assertType("'bar'|'baz'|'foo'", $result);
