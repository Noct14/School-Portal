<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Handle specific exceptions (like 500 internal server error)
        if ($exception instanceof \Illuminate\Http\Client\RequestException) {
            // Optionally log the exception details for internal use
            \Log::error("cURL error: " . $exception->getMessage());

            // Return a generic message to the user
            return response()->json(['message' => 'An unexpected error occurred. Please try again later.'], 500);
        }

        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            // Return custom 404 message if model or route not found
            return response()->json(['message' => 'Resource not found'], 404);
        }

        // For general exception handling, return a 500 with a custom message
        if (config('app.debug') === false) {
            // Don't reveal details to the user in production
            return response()->json(['message' => 'An error occurred. Please try again later.'], 500);
        }

        return parent::render($request, $exception);
    }
}
