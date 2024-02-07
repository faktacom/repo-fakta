<?php

namespace App\Http\Middleware\Korporat;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role_list)
    {
        $allowed = false;
        $role_list = explode("|", $role_list);
        if (Auth::check()) {
            foreach ($role_list as $role) {
                if (Auth::user()->role_id == $role) {
                    $allowed = true;
                    break;
                }
            }
        }

        if ($allowed == true) {
            return $next($request);
        } else {
            return redirect()->route('admin.login');
        }
    }
}
