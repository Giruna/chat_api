<?php

use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        $middleware->api(prepend: [
            ForceJsonResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (
            QueryException $exception,
            Request        $request
        ) {
            if ((string) $exception->getCode() === '2002') {
                return response()->json([
                    'success' => false,
                    'error'   => 'DATABASE_UNAVAILABLE',
                    'message' => 'Database unavailable.'
                ], 503);
            }

            return response()->json([
                'success' => false,
                'message' => 'Database error: '.$exception->getCode()
            ]);
        });
        $exceptions->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
            }

            return response()->json([
                'success' => false,
                'message' => 'An error occurred.'
            ], 500);
        });
    })->create();
