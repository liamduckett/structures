<?php

use function Liamduckett\Structures\iterable_empty;
use function PHPStan\Testing\assertType;

$result = iterable_empty([1, 2, 3]);

assertType('bool', $result);

$result = iterable_empty([]);

assertType('bool', $result);
