<?php

namespace Tests\Dictionary\Concerns;

use Liamduckett\Structures\Concerns\BuildsTypedIterator;
use Liamduckett\Structures\Dictionary;

trait TestsDictionaries
{
    use BuildsTypedIterator;

    /**
     * @template T of mixed
     *
     * @param Dictionary<T>              $dict
     * @param array<non-empty-string, T> $expected
     */
    protected function assertDictionary(Dictionary $dict, array $expected): void
    {
        $this->assertSame($expected, $dict->array());
    }
}
