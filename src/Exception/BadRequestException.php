<?php

namespace App\Exception;

use Exception;

class BadRequestException extends Exception implements ApiExceptionInterface
{
    public function __construct(string $message = 'Bad request', Exception $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}