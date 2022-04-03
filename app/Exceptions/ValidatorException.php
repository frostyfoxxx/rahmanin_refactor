<?php

namespace App\Exceptions;


use Exception;

class ValidatorException extends Exception
{
    public $validatorObject;

    public function __construct($message = "", $code = 0, $validatorObject, Throwable $previous = null)
    {
        $this->validatorObject = $validatorObject;
        parent::__construct($message, $code, $previous);
    }

    public function getValidatorObject()
    {
        return $this->validatorObject;
    }

    public function setValidatorObject($validatorObject)
    {
        $this->validatorObject = $validatorObject;
    }
}