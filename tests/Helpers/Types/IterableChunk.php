<?php

use function Liamduckett\Structures\iterable_chunk;
use function PHPStan\Testing\assertType;

$result = iterable_chunk([1, 2, 3, 4, 5], 2);

assertType('Generator<int, Generator<0|1|2|3|4, int, mixed, void>, mixed, void>', $result);

$result = iterable_chunk(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], 2);

assertType("Generator<int, Generator<'a'|'b'|'c'|'d'|'e', int, mixed, void>, mixed, void>", $result);

/** @var array<int, int> $intArray */
$intArray = [];
$result = iterable_chunk($intArray, 2);

assertType('Generator<int, Generator<int, int, mixed, void>, mixed, void>', $result);

/** @var array<non-empty-string, int> $notEmptyStringArray */
$notEmptyStringArray = [];
$result = iterable_chunk($notEmptyStringArray, 2);

assertType('Generator<int, Generator<non-empty-string, int, mixed, void>, mixed, void>', $result);
