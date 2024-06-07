<?php

namespace Jnewer\ExceptionHandler\Exception;

/**
 * MethodNotAllowedHttpException represents a "Method Not Allowed" HTTP exception with status code 405.
 *
 * @see https://tools.ietf.org/html/rfc7231#section-6.5.5
 * @author Jnewer <jeekang@qq.com>
 */
class MethodNotAllowedHttpException extends HttpException{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(405, $message, $code, $previous);
    }
}