<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = $request->user();
        if (!$user){
            return redirect()->route('login');
        }
        if (! in_array($user->type , $types)){
            abort(403, 'You are not allowed to access this page!');
        }
        return $next($request);

//        $response = $next($request);
//        return $response->header('x-powerd-by', 'Laravel');
    }
}
