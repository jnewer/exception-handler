<?php

namespace Jnewer\ExceptionHandler\Exception;

use Webman\Http\Request;
use Webman\Http\Response;
use Exception;
use Throwable;

class BaseException extends Exception
{
    public function render(Request $request, Throwable $exception): Response
    {
        $message = $exception->getMessage();
        $statusCode = (property_exists($exception, 'statusCode') && $exception->statusCode) ? (int) $exception->statusCode : 500;
        if (!$request->expectsJson()) {
            return new Response($statusCode, [], $message);
        }

        $jsonMessage = ['code' => $statusCode, 'message' => $message, 'success' => false, 'data' => []];
        return new Response(
            $statusCode,
            ['Content-Type' => 'application/json'],
            json_encode($jsonMessage, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
