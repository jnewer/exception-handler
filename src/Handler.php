<?php

namespace Jnewer\ExceptionHandler;

use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;
use support\exception\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    public $dontReport = [];

    public function report(Throwable $exception)
    {
        $this->dontReport = config('plugin.jnewer.exception-handler.app.exception.dont_report', []);
        parent::report($exception);
    }
    public function getExceptionHandlers()
    {
        return config('plugin.jnewer.exception-handler.app.exception.handlers', []);
    }

    public function getHandler($exception)
    {
        $handlers = $this->getExceptionHandlers();

        foreach ($handlers as $exceptionClass => $handler) {
            if ($exceptionClass === get_class($exception) || is_subclass_of($exception, $exceptionClass)) {
                return $handler;
            }
        }

        return null;
    }

    public function render(Request $request, Throwable $exception): Response
    {
        $handler = $this->getHandler($exception);
        if ($handler && is_subclass_of($handler, ExceptionHandler::class)) {
            return (new $handler($this->logger, $this->debug))->render($request, $exception);
        }

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'code' => $exception->getCode() ?: 1,
                'message' => $exception->getMessage(),
                'success' => false,
                'data' => []
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
