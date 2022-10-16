<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronLog extends Model
{
    protected $fillable = [
        'description',
        'is_success',
        'exception',
        'start_time',
        'end_time'
    ];

    static function log( $description, $isSuccess, $start_time = 0, $exception = '' ) {

        $data = [
            'description'     => $description,
            'is_success'      => $isSuccess,
            'exception'       => $exception,
            'start_time'      => ( $start_time ) ? $start_time : now(),
            'end_time'        => now()
        ];

        return self::create( $data );
    }
}
