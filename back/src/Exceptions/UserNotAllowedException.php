<?php

namespace App\Exceptions;

class UserNotAllowedException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct(" User Not Found");
    }
}