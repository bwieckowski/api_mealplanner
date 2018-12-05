<?php

namespace App\Exception;

use Exception;

class AccessDeniedException extends Exception implements ApiExceptionInterface
{
    public function __construct(string $message = 'Access denied', Exception $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }
}