<?php

namespace Jnewer\ExceptionHandler;

use Webman\Http\Request;
use Webman\Http\Response;
use support\exception\Handler;
use Illuminate\Validation\ValidationException;
use Throwable;
use function json_encode;

class ValidationExceptionHandler extends Handler
{
    public function render(Request $request, Throwable $exception): Response
    {
        /** @var ValidationException $exception */
        $message = $exception->validator->errors()->first();
        if (!$request->expectsJson()) {
            return new Response($exception->status, [], $message);
        }

        return new Response(
            $exception->status,
            ['Content-Type' => 'application/json'],
            json_encode([
                'code' => $exception->status,
                'message' => $message,
                'success' => false,
                'data' => []
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
