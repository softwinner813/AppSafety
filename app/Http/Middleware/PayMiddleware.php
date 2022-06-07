<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PayMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // var_dump(auth()->user()->membership_end_date);die();
        if (auth()->user()->membership_end_date < date('Y-m-d')) {
            return redirect()->back();
        }
        return $next($request);
    }
}
