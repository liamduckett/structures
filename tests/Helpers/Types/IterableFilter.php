<?php

use function Liamduckett\Structures\iterable_filter;
use function PHPStan\Testing\assertType;

$result = iterable_filter([1, 2, 3], fn (int $value) => $value > 1);

assertType('Generator<int, int, null, void>', $result);

$result = iterable_filter(['a' => 1, 'b' => 2], fn (int $value, string $key) => 'b' !== $key);

assertType('Generator<string, int, null, void>', $result);
