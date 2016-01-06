<?php

namespace App\Http\Middleware;

use Closure;

class CheckfanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(is_numeric($request->input('fid')) && $request->input('fid') > 0){
            return $next($request);
        }
        return abort(404);
    }
}
