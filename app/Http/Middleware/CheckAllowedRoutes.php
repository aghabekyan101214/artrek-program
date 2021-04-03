<?php

namespace App\Http\Middleware;

use App\Model\UserAllowedRoutes;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class CheckAllowedRoutes
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
        if ($user->role == User::SUPER_ADMIN) {
            return $next($request);
        } else {
            $currentPath = Route::getFacadeRoot()->current()->getName();
            $answer = UserAllowedRoutes::where(['route' => $currentPath, 'user_id' => $user->id])->first();
            if (is_null($answer)) {
                abort(404);
            } else {
                $allowed_routes = UserAllowedRoutes::select('route')->where(['user_id' => $user->id])->get();
                $allowed_routes->toArray();
                view()->share('whitelist_routes', $allowed_routes);
                return $next($request);
            }
        }
    }

}
