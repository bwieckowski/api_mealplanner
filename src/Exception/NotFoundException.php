<?php

namespace App\Exception;

use Exception;

class NotFoundException extends Exception implements ApiExceptionInterface
{
    public function __construct(string $message = 'Not found', Exception $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}