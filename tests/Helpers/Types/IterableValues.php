<?php

use function Liamduckett\Structures\iterable_values;
use function PHPStan\Testing\assertType;

$result = iterable_values([1, 2, 3]);

assertType('Generator<int, int, null, void>', $result);

$result = iterable_values(['a' => 'foo', 'b' => 'bar']);

assertType('Generator<int, string, null, void>', $result);
