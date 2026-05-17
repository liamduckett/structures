<?php

use function Liamduckett\Structures\iterable_last;
use function PHPStan\Testing\assertType;

$result = iterable_last([1, 2, 3]);

assertType('1|2|3', $result);

$result = iterable_last(['foo', 'bar', 'baz']);

assertType("'bar'|'baz'|'foo'", $result);
