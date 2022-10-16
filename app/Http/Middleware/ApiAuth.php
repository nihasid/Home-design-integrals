<?php

namespace App\Http\Middleware;

use App\Helpers\Constant;
use App\Helpers\ResponseHandler;
use App\Models\AccessToken;
use Closure;
use Illuminate\Support\Facades\Auth;

class ApiAuth
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
        $accessToken = '';

        if($request->cookie('token')){
            $accessToken = $request->cookie('token');
        } elseif($request->access_token){
            $accessToken = $request->access_token;
        }

        $request->request->add(['token' => $accessToken]);

        if($accessToken){
            $condition = [
                ['access_token', '=', $accessToken],
                ['expired_at', '>', date(Constant::DATE_TIME_FORMAT)]
            ];

            $token = AccessToken::where($condition)->first();

            if($token){
                Auth::onceUsingId($token['user_id']);
                return $next($request);
            }
        }

        return ResponseHandler::authenticationError();
    }
}
