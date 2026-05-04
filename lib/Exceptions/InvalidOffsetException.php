<?php

namespace Liamduckett\Structures\Exceptions;

use Exception;

class InvalidOffsetException extends Exception
{
    public function __construct()
    {
        parent::__construct('Cannot add item without offset');
    }
}
