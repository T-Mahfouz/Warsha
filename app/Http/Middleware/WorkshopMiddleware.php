<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class WorkshopMiddleware
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
        $user = Auth::user();
        $Workshop = false;
        if($user->role_id == 2)
            $Workshop = true;
        if(!$Workshop)
        {
            if($request->wantsJson())
                return response()->json([
                    'code' => 401,
                    'message' => 'Unauthorized, user not Workshop.',
                    'data' => []
                ],444);
            Auth::guard()->logout();
            $request->session()->invalidate();
            return redirect('/');
        }

        return $next($request);
    }
}
