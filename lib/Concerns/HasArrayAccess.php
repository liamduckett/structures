<?php

namespace Liamduckett\Structures\Concerns;

use Liamduckett\Structures\Exceptions\InvalidOffsetException;
use Liamduckett\Structures\Exceptions\OffsetDoesntExistException;

/**
 * @template TKey of array-key
 * @template TValue of mixed
 */
trait HasArrayAccess
{
    /**
     * @param TKey $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param TKey $offset
     *
     * @return TValue
     *
     * @throws OffsetDoesntExistException
     */
    public function offsetGet(mixed $offset): mixed
    {
        return match (isset($this->items[$offset])) {
            true => $this->items[$offset],
            false => throw new OffsetDoesntExistException($offset),
        };
    }

    /**
     * @param ?TKey $offset
     * @param TValue $value
     *
     * @throws InvalidOffsetException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            throw new InvalidOffsetException;
        }

        $this->items[$offset] = $value;
    }

    /**
     * @param TKey $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }
}
