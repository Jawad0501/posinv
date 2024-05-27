<?php

namespace App\Http\Middleware;

use App\Models\Staff;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        foreach ($request->user()->role?->permissions as $permission) {
            Gate::define($permission->slug, function (Staff $staff) use ($permission) {
                return $staff->hasPermission($permission->slug);
            });
        }

        return $next($request);
    }
}
