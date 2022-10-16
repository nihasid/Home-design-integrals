<?php

namespace App\Http;

use App\Http\Middleware\ACLChecks;
use App\Http\Middleware\AddPaginationRequest;
use App\Http\Middleware\ApiAuth;
use App\Http\Middleware\CheckForMaintenanceMode;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use ReqRespLog\Middleware\RequestResponseLogger;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'api' => [
            'throttle:500,1',
            'bindings',
            'reqRespLog',
            AddPaginationRequest::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'bindings'          => SubstituteBindings::class,
        'throttle'          => ThrottleRequests::class,
        'apiAuth'           => ApiAuth::class,
        'ACL'               => ACLChecks::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        RequestResponseLogger::class,
        ApiAuth::class,
        ACLChecks::class,
        SubstituteBindings::class,
    ];
}
