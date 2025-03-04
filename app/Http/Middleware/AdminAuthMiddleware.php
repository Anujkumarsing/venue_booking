<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('admin')->user();

        if (!$user || !in_array($user->user_type_id, [1, 2])) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Restrict subadmin from certain routes
        if ($user->user_type_id == 2) {
            $restrictedRoutes = [
                'manage.sub.admin',
                'save.sub.admin',
                'delete.sub.admin',
                'toggle.status.sub.admin',
            ];

            if (in_array($request->route()->getName(), $restrictedRoutes)) {
                return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
            }
        }

        return $next($request);
    }

}
