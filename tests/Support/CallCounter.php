<?php

namespace Tests\Support;

use Countable;

/**
 * Mutable call counter for laziness tests.
 *
 * PHPStan doesn't track that an int captured by reference in a closure may
 * change when the closure is invoked later.
 *
 * @see https://github.com/phpstan/phpstan/issues/7751
 * @see https://phpstan.org/r/19a080b7-40f5-4f20-afbb-7e7e32925cfb
 */
final class CallCounter implements Countable
{
    /** @var int<0, max> */
    private int $count = 0;

    public function increment(): void
    {
        ++$this->count;
    }

    public function count(): int
    {
        return $this->count;
    }
}
