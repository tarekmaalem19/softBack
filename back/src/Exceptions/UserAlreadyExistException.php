<?php

namespace App\Exceptions;

class UserAlreadyExistException extends \RuntimeException
{
    public function __construct(string $name)
    {
        parent::__construct("User #" . $name . " User Already exist");
    }
}