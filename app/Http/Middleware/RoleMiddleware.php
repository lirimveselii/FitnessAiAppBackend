<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;



class RoleMiddleware
{
      /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
       
        // if ($role !== null) {
        //     if (!auth()->check() || auth()->user()->role !== $role) {
        //         abort(403, 'Unauthorized');
        //     }
        // }

        // return $next($request);
        dd("test");
        
    }

    
}
