<?php

namespace App\Http\Middleware;

use Closure;
class AuthHome
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
        if (auth()->guard('home')->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }else{
                return redirect()->guest('login');
            }
        }
        return $next($request);
    }
}
