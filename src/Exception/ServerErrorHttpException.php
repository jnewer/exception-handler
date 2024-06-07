<?php

namespace Jnewer\ExceptionHandler\Exception;

/**
 * ServerErrorHttpException represents an "Internal Server Error" HTTP exception with status code 500.
 *
 * @see https://tools.ietf.org/html/rfc7231#section-6.6.1
 * @author Jnewer <jeekang@qq.com>
 */
class ServerErrorHttpException extends HttpException{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(500, $message, $code, $previous);
    }
}