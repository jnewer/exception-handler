<?php

use Illuminate\Validation\ValidationException;
use Jnewer\ExceptionHandler\Exception\HttpException;
use Jnewer\ExceptionHandler\HttpExceptionHandler;
use Jnewer\ExceptionHandler\ValidationExceptionHandler;

return [
    'enable' => true,
    'exception' => [
        'handlers' => [
            ValidationException::class => ValidationExceptionHandler::class,
            HttpException::class => HttpExceptionHandler::class
        ]
    ]
];
