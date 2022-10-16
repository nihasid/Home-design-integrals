<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExceptionLog extends Model
{
    protected $fillable = [
        'type',
        'route_action',
        'line',
        'exception',
    ];

    static function log ( $e, $routeAction = '', $type = 'handled-exception' ) {

        if (!env('EXCEPTION_LOGGING')) {
            return true;
        }

        $log = new self;

        $log->fill([
            'type'              => $type,
            'route_action'      => $routeAction,
            'line'              => $e ? $e->getLine() : '',
            'exception'         => $e ? $e->getMessage() . ' : ' . $e->getTraceAsString() : ''
        ]);

        $log->save();

        return $log;
    }
}
