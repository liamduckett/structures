<?php

use ArrayIterator;

use function Liamduckett\Structures\iterable_count;
use function PHPStan\Testing\assertType;

$result = iterable_count([1, 2, 3]);

assertType('int<0, max>', $result);

$result = iterable_count(new ArrayIterator([1, 2, 3]));

assertType('int<0, max>', $result);
