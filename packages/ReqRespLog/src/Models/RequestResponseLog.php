<?php

namespace ReqRespLog\Models;

use App\Helpers\Constant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RequestResponseLog extends Model
{
    protected $fillable = [
        'user_id',
        'request_id',
        'url',
        'ip',
        'method',
        'http_status',
        'time_in',
        'time_out',
        'request_data',
        'response_data',
        'request_headers',
        'access_token'
    ];

    public static function logRequest( $request )
    {
        $log = new RequestResponseLog;

        $log->fill([
            'request_id'        => $request->request_id,
            'user_id'           => Auth::check() ? Auth::id() : 0,
            'access_token'      => $request->access_token ? $request->access_token : $request->cookie('token', ''),
            'url'               => $request->path(),
            'ip'                => $request->getClientIp(),
            'method'            => $request->getMethod(),
            'time_in'           => now()->format('H:i:s'),
            'request_data'      => serialize( $request->except( Constant::UNSERIALIZABLE_FIELDS ) ),
            'request_headers'   => serialize( $request->header() ),
        ]);

        $log->save();
    }

    public static function logResponse( $request, $response )
    {
        $log = RequestResponseLog::where('request_id', $request->request_id)->first();

        $log->fill([
            'user_id'           => Auth::check() ? Auth::id() : 0,
            'time_out'          => now()->format('H:i:s'),
            'http_status'       => $response->getStatusCode(),
            'response_data'     => serialize(json_decode($response->getContent(), true))
        ]);
        $log->save();
    }
}
