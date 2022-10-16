<?php

namespace App\Http\Middleware;

use App\Helpers\Constant;
use App\Helpers\ResponseHandler;
use App\Models\Module;
use Closure;
use Illuminate\Support\Facades\Auth;

class ACLChecks
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $acl = true;
        $user = Auth::user();

        $routeModule = $request->segment(3);
        $publicRoutes = [
            'auth',
            'logout'
        ];

        if (!$acl || !$user || in_array($routeModule, $publicRoutes)) {
            return $next($request);
        }

        $userRoleId = $user->userRole->role_id;

        // If super-user or first login attempt
        if ($userRoleId == Constant::USER_ROLES['superAdmin']) {
            return $next($request);
        }

        $permissionLevel = Constant::ACL_PERMISSIONS[$request->method()];

        $moduleAccess = Module::whereHas('rolePermissions', function ($q) use ($permissionLevel, $userRoleId) {
            $condition = [
                'role_id'   => $userRoleId,
                'is_active' => 1
            ];

            $q->where($condition);
            $q->whereIn('permission_level', $permissionLevel);

        })->where([
            'route'     => $routeModule,
            'is_active' => 1
        ])->first();

        if (!$moduleAccess) {
            return ResponseHandler::authorizationError();
        } else {
            return $next($request);
        }
    }
}
