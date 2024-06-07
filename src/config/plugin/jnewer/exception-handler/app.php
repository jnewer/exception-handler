<?php

use support\exception\BusinessException;
use Illuminate\Validation\ValidationException;
use Jnewer\ExceptionHandler\BaseExceptionHandler;
use Jnewer\ExceptionHandler\Exception\BaseException;
use Jnewer\ExceptionHandler\ValidationExceptionHandler;

return [
    'enable' => true,
    'exception' => [
        'handlers' => [
            ValidationException::class => ValidationExceptionHandler::class,
            BaseException::class => BaseExceptionHandler::class
        ]
    ],
    'dont_report' => [
        BusinessException::class,
        BaseException::class,
        ValidationException::class,
    ]
];
