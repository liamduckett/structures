<?php

use function Liamduckett\Structures\iterable_map;
use function PHPStan\Testing\assertType;

$result = iterable_map([1, 2, 3], static fn (int $value) => $value * 2);

assertType('Generator<int, int, null, void>', $result);

$result = iterable_map(['a' => 1, 'b' => 2], static fn (int $value, string $key) => $key.$value);

assertType('Generator<string, non-empty-string, null, void>', $result);
