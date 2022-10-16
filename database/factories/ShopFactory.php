<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Shop;
use Faker\Generator as Faker;

$factory->define(Shop::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'country_id' => 1,
        'created_by' => 1,
        'created_at' => now()
    ];
});
