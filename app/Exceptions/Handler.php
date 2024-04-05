<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    //
    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     if ($request->expectsJson()) {
    //         return response()->json(['message' => 'Unauthenticated.'], 401);
    //     }

    //     return redirect()->guest(route('login')); // This line is for web routes, adjust or remove as needed.
    // }

    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     if ($request->expectsJson()) {
    //         return response()->json(['error' => 'Unauthenticated.'], 401);
    //     }

    //     return redirect()->guest('/');
    // }
    protected function unauthenticated($request, AuthenticationException $exception)
{
    if ($request->expectsJson()) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    // return redirect()->guest(route('login')); // This line is for web routes, adjust or remove as needed.
}
}
