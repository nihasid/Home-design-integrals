<?php

namespace App\Http\Middleware;

use App\Helpers\Constant;
use Closure;

class AddPaginationRequest
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
        if ($request->page) {
            $page = $request->page;

            $limit = Constant::DEFAULT_DB_RECORDS_LIMIT;
            $offset = ( $limit * $page ) - Constant::DEFAULT_DB_RECORDS_LIMIT;

            $request->request->add([
                'limit' => $limit,
                'offset' => $offset
            ]);
        }

        return $next($request);
    }
}
