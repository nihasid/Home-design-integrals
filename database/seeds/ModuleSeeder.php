<?php

use App\Models\ExceptionLog;
use App\Models\Module;
use App\Models\AdminUserDetail;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {

            DB::beginTransaction();

            $modules = [
                [
                    'id'            => 1,
                    'module_name'   => 'Users',
                    'module_type'   => 'admin',
                    'route'         => 'users',
                    'icon_class'    => 'cust-icon-users',
                    'active_class'  => '',
                    'display_order' => 5,
                ],
                [
                    'id'            => 2,
                    'module_name'   => 'Vendors',
                    'module_type'   => 'admin',
                    'route'         => 'vendors',
                    'icon_class'    => 'cust-icon-vendors',
                    'active_class'  => '',
                    'display_order' => 4,
                ],
                [
                    'id'            => 3,
                    'module_name'   => 'Shops',
                    'module_type'   => 'admin',
                    'route'         => 'shops',
                    'icon_class'    => 'cust-icon-shops',
                    'active_class'  => '',
                    'display_order' => 3,
                ],
                [
                    'id'            => 4,
                    'module_name'   => 'Projects',
                    'module_type'   => 'admin',
                    'route'         => 'projects',
                    'icon_class'    => 'cust-icon-projects',
                    'active_class'  => '',
                    'display_order' => 2,
                ],
                [
                    'id'            => 5,
                    'module_name'   => 'Inventory',
                    'module_type'   => 'admin',
                    'route'         => 'inventory',
                    'icon_class'    => 'cust-icon-reports disabled',
                    'active_class'  => '',
                    'display_order' => 6,
                ],
                [
                    'id'            => 6,
                    'module_name'   => 'Dashboard',
                    'module_type'   => 'admin',
                    'route'         => 'index',
                    'icon_class'    => 'cust-icon-dashboard',
                    'active_class'  => '',
                    'display_order' => 1,
                ]
            ];
            Module::truncate();
            Module::insert($modules);

        } catch (Exception $e) {
            DB::rollBack();
            ExceptionLog::log($e, static::class);
        } finally {
            DB::commit();
        }
    }
}
