<?php

use function Liamduckett\Structures\iterable_to_generator;
use function PHPStan\Testing\assertType;

$result = iterable_to_generator([1, 2, 3]);

assertType('Generator<0|1|2, int, mixed, void>', $result);

$result = iterable_to_generator(['a' => 1, 'b' => 2]);

assertType("Generator<'a'|'b', int, mixed, void>", $result);

/** @var array<int, int> $intArray */
$intArray = [];
$result = iterable_to_generator($intArray);

assertType('Generator<int, int, mixed, void>', $result);

/** @var array<non-empty-string, int> $notEmptyStringArray */
$notEmptyStringArray = [];
$result = iterable_to_generator($notEmptyStringArray);

assertType('Generator<non-empty-string, int, mixed, void>', $result);
