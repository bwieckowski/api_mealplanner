<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

class BadCredentialsException extends HttpException implements ApiExceptionInterface
{
    public function __construct(string $message = "Bad credentials", Exception $previous = null)
    {
        parent::__construct(400, $message, $previous);
    }
}