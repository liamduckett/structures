<?php

namespace Tests\Concerns;

use Exception;
use Generator;

trait TestsGenerators
{
    /**
     * @template TKey of int|string
     * @template T
     *
     * @param non-empty-array<TKey, T> $items
     *
     * @return Generator<TKey, T, mixed, void>
     */
    protected function buildPartiallyConsumedGenerator(array $items): Generator
    {
        $generator = self::yieldFrom($items);

        $generator->next();

        return $generator;
    }

    protected function expectGeneratorAlreadyRunException(): void
    {
        $this->expectExceptionObject(new Exception('Cannot rewind a generator that was already run'));
    }

    protected function expectGeneratorAlreadyClosedException(): void
    {
        $this->expectExceptionObject(new Exception('Cannot traverse an already closed generator'));
    }

    /**
     * @template TKey of int|string
     * @template T
     *
     * @param array<TKey, T> $items
     *
     * @return Generator<TKey, T, mixed, void>
     */
    protected static function yieldFrom(array $items): Generator
    {
        yield from $items;
    }
}
