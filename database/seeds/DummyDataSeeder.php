<?php

use App\Models\Project;
use App\Models\Shop;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vendor::truncate();
        foreach (factory(Vendor::class, 30)->make() as $vendor) {
            $vendor->save();
        }

        Project::truncate();
        foreach (factory(Project::class, 30)->make() as $project) {
            $project->save();
        }

        Shop::truncate();
        foreach (factory(Shop::class, 30)->make() as $project) {
            $project->save();
        }
    }
}
