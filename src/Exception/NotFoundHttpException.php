<?php

namespace Jnewer\ExceptionHandler\Exception;

/**
 * NotFoundHttpException represents a "Not Found" HTTP exception with status code 404.
 *
 * @see https://tools.ietf.org/html/rfc7231#section-6.5.4
 * @author Jnewer <jeekang@qq.com>
 */
class NotFoundHttpException extends HttpException{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(404, $message, $code, $previous);
    }
}