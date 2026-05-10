<?php

namespace Liamduckett\Structures\Exceptions;

class OffsetDoesntExistException extends RuntimeException
{
    public function __construct(string $offset)
    {
        parent::__construct("Unable to find item at offset {$offset}");
    }
}
