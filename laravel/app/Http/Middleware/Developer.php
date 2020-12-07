<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Developer
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
        if (Auth::check()) {
          if (Auth::user()->role == 'dev'||Auth::user()->role == 'admin') {
            return $next($request);
          } else {
            return back();
          }
        }
        return $next($request);
    }
}
