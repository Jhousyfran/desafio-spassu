<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResourceNotFoundException extends HttpException
{
    public function __construct($message = 'Resource not found', $code = 404)
    {
        parent::__construct($code, $message);
    }
}
