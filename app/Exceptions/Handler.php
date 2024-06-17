<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->renderable(function (ModelNotFoundException $e, $request) {
            return response()->json(['error' => 'Resource not found'], 404);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json(['error' => 'Method not allowed'], 405);
        });

        $this->renderable(function (Throwable $e, $request) {
            // Log the exception details for debugging
            if (app()->environment('local')) {
                return response()->json([
                    'error' => 'Server error',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ], 500);
            }

            return response()->json(['error' => 'Server Error'], 500);
        });
        $this->reportable(function (Throwable $e) {
            //
        });
    }

}
