<?php

use function Liamduckett\Structures\value;
use function PHPStan\Testing\assertType;

assertType('array{1, 2, 3}', value([1, 2, 3]));

assertType('Generator<int, 1|2, mixed, void>', value(function () {
    yield 1;

    yield 2;
}));
