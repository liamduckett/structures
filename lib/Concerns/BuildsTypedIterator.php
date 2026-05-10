<?php

namespace Liamduckett\Structures\Concerns;

use ArrayIterator;

trait BuildsTypedIterator
{
    /**
     * Used to work around a static analysis limitation.
     *
     * @see https://github.com/phpstan/phpstan/issues/10289
     *
     * @template TValue
     *
     * @param array<non-empty-string, TValue> $items
     *
     * @return ArrayIterator<non-empty-string, TValue>
     */
    protected function buildTypedIterator(array $items): ArrayIterator
    {
        /** @var ArrayIterator<non-empty-string, TValue> $iterator */
        $iterator = new ArrayIterator($items);

        return $iterator;
    }
}
