<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'shop_id' => 1,
        'vendor_id' => 1,
        'region_id' => 1,
        'country_id' => 1,
        'project_generation' => 'GDS',
        'project_status' => 1,
        'created_by' => 1,
        'created_at' => now()
    ];
});
