<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {  
        if (Auth::guard($guards)->check()) {

        if (Auth::user()->can('manage')) {

            return $this->redirect($request, '/admin');
        }

        return $this->redirect($request, '/dashboard');
         }

             return $next($request);
    }
    protected function redirect($request, $route) {


        if ($request->expectsJson()) {

            return response()->json(['url' => url($route)]);

        }

        return redirect($route);
    }
}
