# webman exception handler 异常插件

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jnewer/exception-handler.svg?style=flat-square)](https://packagist.org/packages/jnewer/exception-handler)
[![Total Downloads](https://img.shields.io/packagist/dt/jnewer/exception-handler.svg?style=flat-square)](https://packagist.org/packages/jnewer/exception-handler)
[![License](http://poser.pugx.org/jnewer/exception-handler/license)](https://packagist.org/packages/jnewer/exception-handler)
[![PHP Version Require](http://poser.pugx.org/jnewer/exception-handler/require/php)](https://packagist.org/packages/jnewer/exception-handler)

该插件扩展了webman的Handler类，webman的Handler只能对同一应用下的异常进行统一处理，而该插件可以针对同一应用的不同异常进行不同的处理。
## 安装

```bash
composer require jnewer/exception-handler
```

## 配置

`config/exception.php`

```php
return [
    // 这里配置异常处理类,有单独配置Handler的异常,会自动分发给对应的Handler处理
    '' => \Jnewer\ExceptionHandler\Handler::class,
];
```

`config/plugin/jnewer/exception-handler.php`

```php

use Illuminate\Validation\ValidationException;
use Jnewer\ExceptionHandler\Exception\HttpException;
use Jnewer\ExceptionHandler\HttpExceptionHandler;
use Jnewer\ExceptionHandler\ValidationExceptionHandler;

return [
    'enable' => true,
    'exception' => [
        // 这里配置异常类和对应的处理类
        'handlers' => [
            ValidationException::class => ValidationExceptionHandler::class,
            HttpException::class => HttpExceptionHandler::class
        ],
        'dont_report' => [
            BusinessException::class,
            BaseException::class,
            ValidationException::class,
        ]
    ]
];
```

> 多应用模式时，你可以为每个应用单独配置异常处理类，参见[多应用](https://www.workerman.net/doc/webman/multiapp.html)

## 基本用法

请求参数错误

```php
use support\Request;
use support\Response;
use Jnewer\ExceptionHandler\Exception\NotFoundHttpException;

class UserController{
    public function view($id): Response
    {
        $user = User::find($id);
        if (is_null($user)) {
            throw new NotFoundHttpException('用户不存在');
        }
    }
}
```

以上异常抛出错误信息，如下格式：

```json
HTTP/1.1 404 Not Found
Content-Type: application/json;charset=utf-8

{
    "code": 0,
    "success": false,
    "message": "用户不存在",
    "data": [],
}
```

## 内置异常类

- 客户端异常类（HTTP Status 400）：NotFoundHttpException
- 身份认证异常类（HTTP Status 401）：UnauthorizedHttpException
- 资源授权异常类（HTTP Status 403）：ForbiddenHttpException
- 资源未找到异常类（HTTP Status 404）：NotFoundHttpException
- 请求方法不允许异常类（HTTP Status 405）：MethodNotAllowedHttpException
- 请求内容类型不支持异常类（HTTP Status 406）：NotAcceptableHttpException
- 请求限流在异常类（HTTP Status 429）：TooManyRequestsHttpException
- 服务器内部错误异常类（HTTP Status 500）：ServerErrorHttpException

[更多参考：https://datatracker.ietf.org/doc/html/rfc7231#page-47](https://datatracker.ietf.org/doc/html/rfc7231#page-47)


## 自定义异常类

```php
<?php

namespace support\exception;

use Jnewer\ExceptionHandler\Exception\BaseException;

class InvalidArgumentException extends BaseException
{
}
```

### 使用异常类

```php
use support\Request;
use support\Response;
use support\exception\InvalidArgumentException;

class UserController{
    public function create(Request $request): Response
    {
        if (!$request->post('name')) {
            throw new InvalidArgumentException('参数有误');
        }
    }
}
```

## 内置异常类Handler

- 扩展自webman的Handler：Handler，可以针对不同的异常类进行不同的处理。
- BaseException异常处理：BaseExceptionHandler
- laravel验证器异常处理：ValidationExceptionHandler

## 自定义异常类Handler

```php
<?php

namespace support\exception;
use support\exception\Handler;
use Illuminate\Validation\ValidationException;
use Throwable;
use function json_encode;

class InvalidArgumentExceptionHandler extends Handler
{
    public function render(Request $request, Throwable $exception): Response
    {
        $message = $exception->getMessage();
        $statusCode = $exception->statusCode ?? 500;
        if (!$request->expectsJson()) {
            return new Response($statusCode, [], $message);
        }

        $jsonMessage = ['code' => $exception->code ?: $exception->statusCode, 'message' => $message, 'success' => false, 'data' => []];
        return new Response(
            $statusCode,
            ['Content-Type' => 'application/json'],
            json_encode($jsonMessage, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
```

### 配置Handler
`config/plugin/jnewer/exception-handler.php`

```php

use app\exception\InvalidArgumentException;
use app\exception\InvalidArgumentExceptionHandler;

return [
    'exception' => [
        // 这里配置异常类和对应的处理类
        'handlers' => [
            ...// 其他异常和处理类
            InvalidArgumentException::class => InvalidArgumentExceptionHandler::class
        ]
    ]
];
```