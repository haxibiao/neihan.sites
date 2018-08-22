<?php

namespace App\Exceptions;

use Exception;

class UnregisteredException extends Exception
{
    function __construct($msg='')
    {
        parent::__construct($msg);
    }
}
