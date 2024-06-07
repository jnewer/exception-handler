# webman exception handler 异常插件

## 安装

```php
composer require jnewer/exception-handler
```

## 配置

`config/exception.php`

```php
return [
    // 这里配置异常处理类
    '' => \Jnewer\ExceptionHandler\Handler::class,
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

## 内置异常类Handler

- 扩展自webman的Handler：Handler，可以针对不同的异常类进行不同的处理。
- Http异常处理：HttpExceptionHandler
- laravel验证器异常处理：ValidationExceptionHandler
