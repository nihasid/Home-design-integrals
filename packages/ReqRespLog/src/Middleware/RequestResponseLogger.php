<?php

/*
 * Author: Miesam Jafry
 * Dated: 12-November-2018
 */

namespace ReqRespLog\Middleware;

use ReqRespLog\Models\RequestResponseLog;
use Closure;

class RequestResponseLogger
{
    public function handle($request, Closure $next)
    {
        if( env('REQUEST_RESPONSE_LOGGING', false) ){
            $request->request_id = uniqid();
            RequestResponseLog::logRequest( $request );
        }

        return $next($request);
    }

    public function terminate($request, $response)
    {
        if( env('REQUEST_RESPONSE_LOGGING', false) ){
            RequestResponseLog::logResponse( $request, $response );
        }
    }
}
