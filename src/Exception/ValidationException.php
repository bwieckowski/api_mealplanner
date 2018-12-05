<?php

namespace App\Exception;

use Exception;

class ValidationException extends Exception implements ApiExceptionInterface
{
    public function __construct(array $message, Exception $previous = null)
    {
        $message = json_encode($message);
        parent::__construct($message, 400, $previous);
    }
}