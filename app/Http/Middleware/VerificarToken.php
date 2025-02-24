<?php

namespace App\Http\Middleware;

use Closure;

class VerificarToken
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
        //return $next($request);
        if(true){
            return $next($request);
        }
        return response("No puede continuar. Token invalido",404);
    }
}
