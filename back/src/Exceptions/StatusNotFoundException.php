<?php

namespace App\Exceptions;

class StatusNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct(" Please provide a status");
    }
}