<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use News\Traits\ApiResponseTrait;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Log::error('Exception reported: ', ['exception' => $e]);
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return $this->failureResponse('Resource not found', 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->failureResponse('Endpoint not found', 404);
        }

        if ($exception instanceof ValidationException) {
            // Return the validation errors with a 422 status code
            return $this->failureResponse($exception->errors(), 422);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->failureResponse('Unauthenticated. Invalid or expired token.', 401);
        }

        if ($exception instanceof HttpException) {
            return $this->failureResponse($exception->getMessage(), $exception->getStatusCode());
        }

        // Log the exception for debugging
        Log::info('Caught exception: ', ['exception' => $exception]);

        return $this->failureResponse('Internal Server Error', 500);
    }
}
