<?php

use function Liamduckett\Structures\iterable_contains;
use function PHPStan\Testing\assertType;

$result = iterable_contains([1, 2, 3], 2);

assertType('bool', $result);

$result = iterable_contains(['a' => 'foo', 'b' => 'bar'], 'foo');

assertType('bool', $result);
