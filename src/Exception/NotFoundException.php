<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

class NotFoundException extends HttpException implements ApiExceptionInterface
{
    public function __construct(string $message = 'Not found', Exception $previous = null)
    {
        parent::__construct(404, $message, $previous);
    }
}