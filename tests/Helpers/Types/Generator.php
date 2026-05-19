<?php

use function Liamduckett\Structures\generator;
use function PHPStan\Testing\assertType;

$result = generator([1, 2, 3]);

assertType('Generator<int, int, mixed, void>', $result);

$result = generator(['a' => 1, 'b' => 2]);

assertType('Generator<string, int, mixed, void>', $result);
