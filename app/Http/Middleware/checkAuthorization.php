<?php

namespace App\Http\Middleware;

use Closure;

class checkAuthorization
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

        //UNAUTHORIZED
        /* get route name -> method type -> get all permissions -> inarray: method*/
//        try {
        $currentMethod  =   $request->method();
        $currentPath    =   $request->path();
        $userId = Auth::id();
       dd($currentMethod);
            if ($request->user() && $request->user()->type != ‘admin’)
            {
                return new Response(view(‘unauthorized’)->with(‘role’, ‘ADMIN’));
            }
//        } catch (\Exception $e) {
//            // catches all kinds of RuntimeExceptions
//            if ($e instanceof ClientException) {
//                // catch your ClientExceptions
//                return  response()->json([
//                    'error'=> 1 ,
//                    'message' => $e->getMessage(),
//                ], 401);
//            } elseif ($e instanceof RequestException) {
//                return  response()->json([
//                    'error'=> 1 ,
//                    'message' => $e->getMessage(),
//                ], 401);
//            }
//        }

//        return  response()->json([
//            'error'=> 1 ,
//            'message' => ResponseMiddleware::$message_by_errorcode [401],
//        ], 401);

//        return $next($request);
    }

//    public function checkPermission($permission =[],$currentPermission="")
//    {
//        $response = false ;
//
//        if(!empty($currentPermission)  )
//        {
//
//            $exist = array_intersect($currentPermission,$permission);
//            if(count($exist) == 0)
//            {
//                $response =  response()->json([
//                    'error'=> 1 ,
//                    'message' => ResponseMiddleware::$message_by_errorcode [403],
//                ], 403);
//            }
//        }
//
//        return $response;
//    }
}
