<?php

namespace App\Exceptions;

class ProjectNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct(" Project Not Found");
    }
}