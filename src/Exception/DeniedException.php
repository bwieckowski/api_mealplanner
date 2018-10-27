<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

class DeniedException extends HttpException implements ApiExceptionInterface
{
    public function __construct(string $message = 'Access denied', Exception $previous = null)
    {
        parent::__construct(403, $message, $previous);
    }
}