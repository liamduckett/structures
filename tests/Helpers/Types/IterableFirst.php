<?php

use function Liamduckett\Structures\iterable_first;
use function PHPStan\Testing\assertType;

$result = iterable_first([1, 2, 3]);

assertType('1|2|3', $result);

$result = iterable_first(['foo', 'bar', 'baz']);

assertType("'bar'|'baz'|'foo'", $result);
