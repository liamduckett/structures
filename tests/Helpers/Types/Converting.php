<?php

use function Liamduckett\Structures\iterable_resume;
use function Liamduckett\Structures\iterable_to_array;
use function Liamduckett\Structures\iterable_to_generator;
use function Liamduckett\Structures\value;
use function PHPStan\Testing\assertType;

// Value ---

assertType('array{1, 2, 3}', value([1, 2, 3]));

assertType('Generator<int, 1|2, mixed, void>', value(static function () {
    yield 1;

    yield 2;
}));

// To Array ---

assertType('array<0|1|2, 1|2|3>', iterable_to_array([1, 2, 3]));
assertType("array<'a'|'b', 1|2>", iterable_to_array(['a' => 1, 'b' => 2]));
assertType('array{}', iterable_to_array([]));

// To Generator ---

$result = iterable_to_generator([1, 2, 3]);

assertType('Generator<0|1|2, int, mixed, void>', $result);

$result = iterable_to_generator(['a' => 1, 'b' => 2]);

assertType("Generator<'a'|'b', int, mixed, void>", $result);

/** @var array<int, int> $intArray */
$intArray = [];
$result = iterable_to_generator($intArray);

assertType('Generator<int, int, mixed, void>', $result);

/** @var array<non-empty-string, int> $notEmptyStringArray */
$notEmptyStringArray = [];
$result = iterable_to_generator($notEmptyStringArray);

assertType('Generator<non-empty-string, int, mixed, void>', $result);

// Resume ---

assertType('array{1, 2, 3}', iterable_resume([1, 2, 3]));

/** @var ArrayIterator<int, string> $arrayIterator */
$arrayIterator = new ArrayIterator();
$result = iterable_resume($arrayIterator);

assertType('ArrayIterator<int, string>', $result);

$result = iterable_resume((static function () {
    yield 'a' => 1;

    yield 'b' => 2;
})());

assertType("Generator<'a'|'b', int, mixed, void>", $result);

$stringKeyedGenerator = (static function () use ($notEmptyStringArray): Generator {
    yield from $notEmptyStringArray;
})();
$result = iterable_resume($stringKeyedGenerator);

assertType('Generator<non-empty-string, int, mixed, void>', $result);
