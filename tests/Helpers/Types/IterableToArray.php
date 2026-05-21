<?php

use function Liamduckett\Structures\iterable_to_array;
use function PHPStan\Testing\assertType;

assertType('array<0|1|2, 1|2|3>', iterable_to_array([1, 2, 3]));
assertType("array<'a'|'b', 1|2>", iterable_to_array(['a' => 1, 'b' => 2]));
assertType('array{}', iterable_to_array([]));
