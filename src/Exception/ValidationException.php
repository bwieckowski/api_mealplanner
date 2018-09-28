<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

class ValidationException extends HttpException implements ApiExceptionInterface
{
    public function __construct(string $message = 'Validation error', Exception $previous = null)
    {
        parent::__construct(400, $message, $previous);
    }
}