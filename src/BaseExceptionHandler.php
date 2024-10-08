<?php

namespace Jnewer\ExceptionHandler;

use Webman\Http\Request;
use Webman\Http\Response;
use support\exception\Handler;
use Throwable;
use function json_encode;

class BaseExceptionHandler extends Handler
{
    public function render(Request $request, Throwable $exception): Response
    {
        $message = $exception->getMessage();
        if (!$request->expectsJson()) {
            return new Response($exception->statusCode, [], $message);
        }

        return new Response(
            $exception->statusCode,
            ['Content-Type' => 'application/json'],
            json_encode([
                'code' => $exception->getCode() ?: $exception->statusCode,
                'message' => $message,
                'success' => false,
                'data' => []
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
