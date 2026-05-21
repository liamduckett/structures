<?php

use function Liamduckett\Structures\iterable_contains_key;
use function PHPStan\Testing\assertType;

assertType('bool', iterable_contains_key(['a' => 1, 'b' => 2], 'a'));

assertType('bool', iterable_contains_key([1, 2, 3], 0));
