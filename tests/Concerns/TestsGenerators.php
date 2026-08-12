<?php

namespace Tests\Concerns;

use Exception;
use Generator;

trait TestsGenerators
{
    /**
     *  Builds a generator whose cursor has advanced past its first yield,
     *  which is the state PHP refuses to rewind.
     *
     *  Pass at least two items. A single item leaves the generator exhausted
     *  rather than partially consumed, which fails differently.
     *
     * @template TKey of int|non-empty-string
     * @template T
     *
     * @param non-empty-array<TKey, T> $items
     *
     * @return Generator<TKey, T>
     */
    protected function buildPartiallyConsumedGenerator(array $items): Generator
    {
        $generator = (static function () use ($items): Generator {
            yield from $items;
        })();

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
}
