<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Vendor;
use Faker\Generator as Faker;

$factory->define(Vendor::class, function (Faker $faker) {
    $name = $faker->company;
    $short_name = Vendor::getVendorShortname($name);
    return [
        'name' => $name,
        'short_name' => $short_name,
        'domain' => $faker->domainName,
        'created_by' => 1,
        'created_at' => now()
    ];
});
