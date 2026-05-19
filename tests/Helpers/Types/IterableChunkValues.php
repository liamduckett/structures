<?php

use function Liamduckett\Structures\iterable_chunk_values;
use function PHPStan\Testing\assertType;

$result = iterable_chunk_values([1, 2, 3, 4, 5], 2);

assertType('Generator<int, Generator<int, int, mixed, void>, mixed, void>', $result);

$result = iterable_chunk_values(['a' => 'x', 'b' => 'y'], 1);

assertType('Generator<int, Generator<int, string, mixed, void>, mixed, void>', $result);
