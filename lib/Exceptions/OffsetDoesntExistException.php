<?php

namespace Liamduckett\Structures\Exceptions;

class OffsetDoesntExistException extends RuntimeException
{
    public function __construct(int|string $offset)
    {
        parent::__construct("Unable to find item at offset {$offset}");
    }
}
