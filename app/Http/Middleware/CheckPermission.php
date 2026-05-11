<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{

    public function handle($request, Closure $next, $permission)
    {
        $user = Auth::user(); // works for admin or user guard 

        if (!$user || !$user->hasPermission($permission)) {
            abort(403, 'ليس لديك صلاحية الوصول لهذه الصفحة.');
        }

        return $next($request);
    }
}
