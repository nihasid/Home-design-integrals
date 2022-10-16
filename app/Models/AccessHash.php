<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AccessHash extends Model {
    protected $fillable = [
        'project_id',
        'token',
        'is_used',
    ];

    static function getHash($request) {
        $hash = AccessHash::where('project_id', $request->project_id)
            ->where('token', $request->LongHash)
            ->where('is_used', 0)
            ->first();
//        dd($hash);
        if ($hash) {
            $hash->is_used = 1;
            $hash->save();
        }
        return $hash;
    }

    static function createHash($request) {
        $random_token = Str::random(32);
        $long_hash = AccessHash::where('token', $random_token)->first();

        if ($long_hash) {
            self::createHash($request);
        } else {
            $access_hash = new self;
            $access_hash->fill([
                'project_id' => isset($request->project_id) ? $request->project_id : 0,
                'token' => $random_token,
            ]);
            $access_hash->save();
        }
        return $access_hash;
    }
}
