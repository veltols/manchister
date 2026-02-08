<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle database/SQL errors
        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            // Log the actual error for debugging
            \Log::error('Database Error: ' . $e->getMessage(), [
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return user-friendly response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later.'
                ], 500);
            }

            return response()->view('errors.500', [], 500);
        });

        // Handle general exceptions
        $exceptions->render(function (\Throwable $e, $request) {
            // Skip if it's a QueryException (already handled above)
            if ($e instanceof \Illuminate\Database\QueryException) {
                return null;
            }

            // Skip 404 errors (let Laravel handle them normally)
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return null;
            }

            // Log the error
            \Log::error('Application Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Only show generic error in production
            if (!config('app.debug')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong. Please try again later.'
                    ], 500);
                }

                return response()->view('errors.500', [], 500);
            }

            // In debug mode, let Laravel show the detailed error
            return null;
        });
    })->create();
