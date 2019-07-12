<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpExceptionInterface::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {   
        
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        return parent::render($request, $e);
    }

    protected function renderHttpException(HttpExceptionInterface $e)
    {
         if (!(config('app.env') === 'local')) {
             $status = $e->getStatusCode();
             $message = $e->getMessage();

            if (empty($message)) {
                switch ($status) {
                    case 400: $message = '不正な操作です。'; break;
                    case 401: $message = '認証に失敗しました。'; break;
                    case 403: $message = 'アクセス権がありません。'; break;
                    case 404: $message = '指定されたページは存在しません。'; break;
                    case 408: $message = 'タイムアウトしました。'; break;
                    case 414: $message = 'リクエストURIが長すぎます。'; break;
                    case 500: $message = 'Internal Server Error'; break;
                    case 503: $message = 'Service Unavailable'; break;
                    default:  $message = 'エラー'; break;
                }
            }
           
            if (strstr(\Request::url(), '/admin')) {
                // 管理画面用エラーページ
                return response()->view("admin.errors.error", ['message' => $message], $status);
            } else {
                // 公開画面用エラーページ
                return response()->view("front.errors.error", ['message' => $message], $status);
            }
        }

        return parent::renderHttpException($e);
    }

}
