<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use InvalidArgumentException;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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

    public function report(Throwable $exception)
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
    public function render($request, Throwable $exception)
    {
        return response()->json(
            [
                'errors' => [
                    'status' => 401,
                    'message' => 'Unauthenticated',
                ]
            ], 401
        );
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->isJson()) {
            return response()->json(['status'=> -5 ,'message' => 'Unauthenticated.'], 200);
        } elseif (is_array($request->route()->computedMiddleware) && in_array('auth:user-api', $request->route()->computedMiddleware)) {
            return response()->json(['status'=> -5 , 'message' => 'you must login first, api-token required'], 200);
        }
        return redirect()->guest('/admin/auth/login');
    }
}
