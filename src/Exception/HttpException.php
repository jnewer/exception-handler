<?php
namespace Jnewer\ExceptionHandler\Exception;
use Webman\Http\Request;
use Webman\Http\Response;

class HttpException extends \Exception{
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
        if(!$message){
            $message = Response::$_phrases[$statusCode] ?? '';
        }
        
        parent::__construct($message, $code, $previous);
    }

    public function render(Request $request, \Throwable $exception): Response
    {
        /** @var ValidationException $exception */
        $message = $exception->validator->errors()->first();
        if (!$request->expectsJson()) {
            return new Response($exception->statusCode, [], $message);
        }

        $jsonMessage = ['code' => $exception->code ?: $exception->statusCode, 'message' => $message, 'success' => false, 'data' => []];
        return new Response(
            $exception->statusCode,
            ['Content-Type' => 'application/json'],
            json_encode($jsonMessage, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

}