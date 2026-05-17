<?php

use function Liamduckett\Structures\iterable_get;
use function PHPStan\Testing\assertType;

$result = iterable_get([1, 2, 3], 0);

assertType('1|2|3', $result);

$result = iterable_get(['foo', 'bar', 'baz'], 0);

assertType("'bar'|'baz'|'foo'", $result);

$result = iterable_get(['a' => 1, 'b' => 2], 'b');

assertType('1|2', $result);
