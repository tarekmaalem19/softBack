<?php

namespace App\Exceptions;

class FileNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct(" Please provide a image");
    }
}