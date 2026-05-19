<?php

use function Liamduckett\Structures\iterable_chunk;
use function PHPStan\Testing\assertType;

$result = iterable_chunk([1, 2, 3, 4, 5], 2);

assertType('Generator<int, Generator<int, int, mixed, void>, mixed, void>', $result);

$result = iterable_chunk(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], 2);

assertType('Generator<int, Generator<string, int, mixed, void>, mixed, void>', $result);
