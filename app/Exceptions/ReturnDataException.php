<?php

namespace App\Exceptions;

use Exception;

class ReturnDataException extends Exception
{
    public function __construct($message = "", $code = 200, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}