<?php

namespace App\Exceptions;

use Exception;

class ValidationExcetion extends Exception
{
    function __construct($msg='')
    {
        parent::__construct($msg);
    }
}
