<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Illuminate\Validation\ValidationException) {

            $e = new Exception(iconv("UTF-8", "GBK//IGNORE", $this->handleValidationException($request, $exception)));
            return parent::render($request, $exception);
//            return response()->json([
//                'msg' => $this->handleValidationException($request, $exception),
//                'code' => 400
//            ]);
        }
        if ($exception) {
            if($request->ajax()){
                if($exception->getMessage()){
                    return response()->json([
                        'msg' => iconv('gbk','utf-8',$exception->getMessage()),
                        'code' => 400
                    ]);
                }
            }
            $code = $exception->getCode();
            if(isset($code) && $exception->getCode() >= 0 && $exception->getMessage()){
                return response()->view('errors.503', [
                    'message' => iconv('gbk','utf-8',$exception->getMessage()),
                    'code' => $exception->getCode()
                ]);
            }
            $showTemplate = 'error';
            if(substr($exception->getStatusCode(),0,1) == 4){
                $showTemplate = '404';
                return response()->view('errors.'.$showTemplate, [
                    'message' => iconv('gbk','utf-8',$exception->getMessage()),
                    'code' => $exception->getStatusCode()
                ],$exception->getStatusCode());
            }else if (substr($exception->getStatusCode(),0,1) == 5){
                $showTemplate = '503';
                return response()->view('errors.'.$showTemplate, [
                    'message' => iconv('gbk','utf-8',$exception->getMessage()),
                    'code' => $exception->getStatusCode()
                ],$exception->getStatusCode());
            }
            if(isset($code) && $exception->getCode() >= 0){
                return parent::render($request, $exception);
            }
        }
        return parent::render($request, $exception);
    }

    /**
     * @param $request
     * @param $e
     * @return null|string
     * 获取自带数据验证错误信息
     */
    protected function handleValidationException($request, $e)
    {
        $errors = @$e->validator->errors()->toArray();
        $message = null;
        if (count($errors)) {
            $firstKey = array_keys($errors)[0];
            $message = @$e->validator->errors()->get($firstKey)[0];
            if (strlen($message) == 0) {
                $message = "An error has occurred when trying to register";
            }
        }
        if ($message == null) {
            $message = "An unknown error has occured";
        }
        return $message;
    }
    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
