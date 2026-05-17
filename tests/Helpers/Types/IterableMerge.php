<?php

use function Liamduckett\Structures\iterable_merge;
use function PHPStan\Testing\assertType;

$result = iterable_merge([1, 2], [3, 4]);

assertType('Generator<int, int, null, void>', $result);

$result = iterable_merge(['a' => 'foo'], ['b' => 'bar']);

assertType('Generator<string, string, null, void>', $result);
