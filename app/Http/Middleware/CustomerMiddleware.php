<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
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
        $customer = false;
        if($user->role_id == 1)
            $customer = true;
        if(!$customer)
        {
            if($request->wantsJson())
                return response()->json([
                    'code' => 401,
                    'message' => 'Unauthorized, user not customer.',
                    'data' => []
                ],444);
            Auth::guard()->logout();
            $request->session()->invalidate();
            return redirect('/');
        }

        return $next($request);
    }
}
