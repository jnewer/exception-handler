<?php

namespace Jnewer\ExceptionHandler\Exception;

use Webman\Http\Request;
use Webman\Http\Response;
use Exception;

class HttpException extends BaseException
{
    /**
     * @var int HTTP status code, such as 403, 404, 500, etc.
     */
    public $statusCode;

    /**
     * Constructor.
     * @param int $status HTTP status code, such as 404, 500, etc.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($statusCode, $message = null, $code = 0, \Exception $previous = null)
    {
        $this->statusCode = $statusCode;
        if (!$message) {
            $message = Response::$_phrases[$statusCode] ?? '';
        }

        parent::__construct($message, $code, $previous);
    }
}
