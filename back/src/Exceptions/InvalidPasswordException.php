<?php

namespace App\Exceptions;

class InvalidPasswordException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct("Invalid Password check credentials");
    }
}