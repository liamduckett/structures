<?php

namespace Liamduckett\Structures\Exceptions;

use Exception;

class OffsetDoesntExistException extends Exception
{
    public function __construct(string $offset)
    {
        parent::__construct("Unable to find item at offset {$offset}");
    }
}
