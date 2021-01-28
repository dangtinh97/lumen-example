<?php

namespace App\Http\Middleware;

use App\Http\Responses\ResponseError;
use App\Http\Responses\StatusCode;
use Closure;
use Illuminate\Support\Facades\Auth;
class Administrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     *

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request);
        }
        return response()->json((new ResponseError(StatusCode::UNAUTHORIZED,'Ban can dang nhap!'))->toArray());
    }
}