<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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

    /**
     * Report or log an exception.
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
        if ($exception instanceof UnauthorizedException)
        {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 401);
        }

        if ($exception instanceof ModelNotFoundException)
        {
            return response()->json([
                'message' => $exception->getMessage(),
                'model' => $exception->getModel(),
                'ids' => $exception->getIds(),
            ], 404);
        }

        if ($exception instanceof ValidationException)
        {
            return response()->json([
                'message' => 'Failed to pass validation.',
                'details' => $exception->errors(),
            ], 400);
        }

        if ($exception instanceof HttpException)
        {
            return response()->json([
                'message' => 'HTTP Error',
                'headers' => $exception->getHeaders(),
            ], $exception->getStatusCode());
        }

        if ($exception instanceof AuthenticationException)
        {
            return response()->json([
                'message' => $exception->getMessage() . ' Please login before continuing.',
            ], 403);
        }
        if ($exception instanceof Exception)
        {

            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
                'trace' => $exception->getTrace(),
                'previous' => $exception->getPrevious()
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
