<?php

use App\Models\ExceptionLog;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
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


            /*$roles = [
                [
                    'id' => 1,
                    'name' => 'Super Admin',
                ],
                [
                    'id' => 2,
                    'name' => 'Admin',
                ],
                [
                    'id' => 3,
                    'name' => 'Samsung HQ',
                ],
                [
                    'id' => 4,
                    'name' => 'Cheil HQ',
                ],
                [
                    'id' => 5,
                    'name' => 'Samsung',
                ],
                [
                    'id' => 6,
                    'name' => 'Cheil',
                ],
                [
                    'id' => 7,
                    'name' => 'Vendor',
                ]
            ];

            Role::truncate();
            Role::insert($roles);

            $userRoles = [
                [
                    'id' => 1,
                    'user_id' => 1,
                    'role_id' => 1,
                ]
            ];

            UserRole::truncate();
            UserRole::insert($userRoles);*/


            $rolePermissions = [
                // Admin role permissions
                [
                    'id' => 1,
                    'role_id' => 2,
                    'module_id' => 1,
                    'permission_level' => 1,
                ],
                [
                    'id' => 2,
                    'role_id' => 2,
                    'module_id' => 1,
                    'permission_level' => 2,
                ],
                [
                    'id' => 3,
                    'role_id' => 2,
                    'module_id' => 1,
                    'permission_level' => 3,
                ],
                [
                    'id' => 4,
                    'role_id' => 2,
                    'module_id' => 1,
                    'permission_level' => 4,
                ],


                [
                    'id' => 5,
                    'role_id' => 2,
                    'module_id' => 2,
                    'permission_level' => 1,
                ],
                [
                    'id' => 6,
                    'role_id' => 2,
                    'module_id' => 2,
                    'permission_level' => 2,
                ],
                [
                    'id' => 7,
                    'role_id' => 2,
                    'module_id' => 2,
                    'permission_level' => 3,
                ],
                [
                    'id' => 8,
                    'role_id' => 2,
                    'module_id' => 2,
                    'permission_level' => 4,
                ],


                [
                    'id' => 9,
                    'role_id' => 2,
                    'module_id' => 3,
                    'permission_level' => 1,
                ],
                [
                    'id' => 10,
                    'role_id' => 2,
                    'module_id' => 3,
                    'permission_level' => 2,
                ],
                [
                    'id' => 11,
                    'role_id' => 2,
                    'module_id' => 3,
                    'permission_level' => 3,
                ],
                [
                    'id' => 12,
                    'role_id' => 2,
                    'module_id' => 3,
                    'permission_level' => 4,
                ],


                [
                    'id' => 13,
                    'role_id' => 2,
                    'module_id' => 4,
                    'permission_level' => 1,
                ],
                [
                    'id' => 14,
                    'role_id' => 2,
                    'module_id' => 4,
                    'permission_level' => 2,
                ],
                [
                    'id' => 15,
                    'role_id' => 2,
                    'module_id' => 4,
                    'permission_level' => 3,
                ],
                [
                    'id' => 16,
                    'role_id' => 2,
                    'module_id' => 4,
                    'permission_level' => 4,
                ],


                [
                    'id' => 17,
                    'role_id' => 2,
                    'module_id' => 5,
                    'permission_level' => 1,
                ],
                [
                    'id' => 18,
                    'role_id' => 2,
                    'module_id' => 5,
                    'permission_level' => 2,
                ],
                [
                    'id' => 19,
                    'role_id' => 2,
                    'module_id' => 5,
                    'permission_level' => 3,
                ],
                [
                    'id' => 20,
                    'role_id' => 2,
                    'module_id' => 5,
                    'permission_level' => 4,
                ],

                // Samsung HQ role permissions
//                [
//                    'id' => 21,
//                    'role_id' => 3,
//                    'module_id' => 1,
//                    'permission_level' => 1,
//                ],
//                [
//                    'id' => 22,
//                    'role_id' => 3,
//                    'module_id' => 1,
//                    'permission_level' => 2,
//                ],
//                [
//                    'id' => 23,
//                    'role_id' => 3,
//                    'module_id' => 1,
//                    'permission_level' => 3,
//                ],
//                [
//                    'id' => 24,
//                    'role_id' => 3,
//                    'module_id' => 1,
//                    'permission_level' => 4,
//                ],


//                [
//                    'id' => 25,
//                    'role_id' => 3,
//                    'module_id' => 2,
//                    'permission_level' => 1,
//                ],
//                [
//                    'id' => 26,
//                    'role_id' => 3,
//                    'module_id' => 2,
//                    'permission_level' => 2,
//                ],
//                [
//                    'id' => 27,
//                    'role_id' => 3,
//                    'module_id' => 2,
//                    'permission_level' => 3,
//                ],
//                [
//                    'id' => 28,
//                    'role_id' => 3,
//                    'module_id' => 2,
//                    'permission_level' => 4,
//                ],


                [
                    'id' => 29,
                    'role_id' => 3,
                    'module_id' => 3,
                    'permission_level' => 1,
                ],
                [
                    'id' => 30,
                    'role_id' => 3,
                    'module_id' => 3,
                    'permission_level' => 2,
                ],
                [
                    'id' => 31,
                    'role_id' => 3,
                    'module_id' => 3,
                    'permission_level' => 3,
                ],
                [
                    'id' => 32,
                    'role_id' => 3,
                    'module_id' => 3,
                    'permission_level' => 4,
                ],


                [
                    'id' => 33,
                    'role_id' => 3,
                    'module_id' => 4,
                    'permission_level' => 1,
                ],
                [
                    'id' => 34,
                    'role_id' => 3,
                    'module_id' => 4,
                    'permission_level' => 2,
                ],
                [
                    'id' => 35,
                    'role_id' => 3,
                    'module_id' => 4,
                    'permission_level' => 3,
                ],
                [
                    'id' => 36,
                    'role_id' => 3,
                    'module_id' => 4,
                    'permission_level' => 4,
                ],


//                [
//                    'id' => 37,
//                    'role_id' => 3,
//                    'module_id' => 5,
//                    'permission_level' => 1,
//                ],
//                [
//                    'id' => 38,
//                    'role_id' => 3,
//                    'module_id' => 5,
//                    'permission_level' => 2,
//                ],
//                [
//                    'id' => 39,
//                    'role_id' => 3,
//                    'module_id' => 5,
//                    'permission_level' => 3,
//                ],
//                [
//                    'id' => 40,
//                    'role_id' => 3,
//                    'module_id' => 5,
//                    'permission_level' => 4,
//                ],

                // Cheil HQ role permissions
//                [
//                    'id' => 41,
//                    'role_id' => 4,
//                    'module_id' => 1,
//                    'permission_level' => 1,
//                ],
//                [
//                    'id' => 42,
//                    'role_id' => 4,
//                    'module_id' => 1,
//                    'permission_level' => 2,
//                ],
//                [
//                    'id' => 43,
//                    'role_id' => 4,
//                    'module_id' => 1,
//                    'permission_level' => 3,
//                ],
//                [
//                    'id' => 44,
//                    'role_id' => 4,
//                    'module_id' => 1,
//                    'permission_level' => 4,
//                ],
//
//
//                [
//                    'id' => 45,
//                    'role_id' => 4,
//                    'module_id' => 2,
//                    'permission_level' => 1,
//                ],
//                [
//                    'id' => 46,
//                    'role_id' => 4,
//                    'module_id' => 2,
//                    'permission_level' => 2,
//                ],
//                [
//                    'id' => 47,
//                    'role_id' => 4,
//                    'module_id' => 2,
//                    'permission_level' => 3,
//                ],
//                [
//                    'id' => 48,
//                    'role_id' => 4,
//                    'module_id' => 2,
//                    'permission_level' => 4,
//                ],


                [
                    'id' => 49,
                    'role_id' => 4,
                    'module_id' => 3,
                    'permission_level' => 1,
                ],
                [
                    'id' => 50,
                    'role_id' => 4,
                    'module_id' => 3,
                    'permission_level' => 2,
                ],
                [
                    'id' => 51,
                    'role_id' => 4,
                    'module_id' => 3,
                    'permission_level' => 3,
                ],
                [
                    'id' => 52,
                    'role_id' => 4,
                    'module_id' => 3,
                    'permission_level' => 4,
                ],


                [
                    'id' => 53,
                    'role_id' => 4,
                    'module_id' => 4,
                    'permission_level' => 1,
                ],
                [
                    'id' => 54,
                    'role_id' => 4,
                    'module_id' => 4,
                    'permission_level' => 2,
                ],
                [
                    'id' => 55,
                    'role_id' => 4,
                    'module_id' => 4,
                    'permission_level' => 3,
                ],
                [
                    'id' => 56,
                    'role_id' => 4,
                    'module_id' => 4,
                    'permission_level' => 4,
                ],


//                [
//                    'id' => 57,
//                    'role_id' => 4,
//                    'module_id' => 5,
//                    'permission_level' => 1,
//                ],
//                [
//                    'id' => 58,
//                    'role_id' => 4,
//                    'module_id' => 5,
//                    'permission_level' => 2,
//                ],
//                [
//                    'id' => 59,
//                    'role_id' => 4,
//                    'module_id' => 5,
//                    'permission_level' => 3,
//                ],
//                [
//                    'id' => 60,
//                    'role_id' => 4,
//                    'module_id' => 5,
//                    'permission_level' => 4,
//                ],


                // Samsung role permissions
//                [
//                    'id' => 61,
//                    'role_id' => 5,
//                    'module_id' => 1,
//                    'permission_level' => 2,
//                ],
                [
                    'id' => 62,
                    'role_id' => 5,
                    'module_id' => 3,
                    'permission_level' => 2,
                ],
                [
                    'id' => 63,
                    'role_id' => 5,
                    'module_id' => 4,
                    'permission_level' => 2,
                ],

                // Cheil role permissions
//                [
//                    'id' => 64,
//                    'role_id' => 6,
//                    'module_id' => 1,
//                    'permission_level' => 2,
//                ],
                [
                    'id' => 65,
                    'role_id' => 6,
                    'module_id' => 3,
                    'permission_level' => 2,
                ],
                [
                    'id' => 66,
                    'role_id' => 6,
                    'module_id' => 4,
                    'permission_level' => 2,
                ],

                // Vendor role permissions
//                [
//                    'id' => 67,
//                    'role_id' => 7,
//                    'module_id' => 1,
//                    'permission_level' => 1,
//                ],
//                [
//                    'id' => 68,
//                    'role_id' => 7,
//                    'module_id' => 1,
//                    'permission_level' => 2,
//                ],
//                [
//                    'id' => 69,
//                    'role_id' => 7,
//                    'module_id' => 1,
//                    'permission_level' => 3,
//                ],
//                [
//                    'id' => 70,
//                    'role_id' => 7,
//                    'module_id' => 1,
//                    'permission_level' => 4,
//                ],


                // shops module
                [
                    'id' => 71,
                    'role_id' => 7,
                    'module_id' => 3,
                    'permission_level' => 2,
                ],

                // projects module
                [
                    'id' => 72,
                    'role_id' => 7,
                    'module_id' => 4,
                    'permission_level' => 2,
                ],

                // reports module
//                [
//                    'id' => 73,
//                    'role_id' => 7,
//                    'module_id' => 5,
//                    'permission_level' => 2,
//                ],



                // user add/edit permission to regional users

//                [
//                    'id' => 74,
//                    'role_id' => 5,
//                    'module_id' => 1,
//                    'permission_level' => 1,
//                ],
//                [
//                    'id' => 75,
//                    'role_id' => 5,
//                    'module_id' => 1,
//                    'permission_level' => 3,
//                ],
//                [
//                    'id' => 76,
//                    'role_id' => 5,
//                    'module_id' => 1,
//                    'permission_level' => 4,
//                ],
//
//                [
//                    'id' => 77,
//                    'role_id' => 6,
//                    'module_id' => 1,
//                    'permission_level' => 1,
//                ],
//                [
//                    'id' => 78,
//                    'role_id' => 6,
//                    'module_id' => 1,
//                    'permission_level' => 3,
//                ],
//                [
//                    'id' => 79,
//                    'role_id' => 6,
//                    'module_id' => 1,
//                    'permission_level' => 4,
//                ],

                // Dashboard Module
                [
                    'id' => 80,
                    'role_id' => 2,
                    'module_id' => 6,
                    'permission_level' => 2,
                ],
                [
                    'id' => 81,
                    'role_id' => 3,
                    'module_id' => 6,
                    'permission_level' => 2,
                ],
                [
                    'id' => 82,
                    'role_id' => 4,
                    'module_id' => 6,
                    'permission_level' => 2,
                ],
                [
                    'id' => 83,
                    'role_id' => 5,
                    'module_id' => 6,
                    'permission_level' => 2,
                ],
                [
                    'id' => 84,
                    'role_id' => 6,
                    'module_id' => 6,
                    'permission_level' => 2,
                ],

            ];

            RolePermission::truncate();
            RolePermission::insert($rolePermissions);

        } catch (Exception $e) {
            DB::rollBack();
            ExceptionLog::log($e, static::class);
        } finally {
            DB::commit();
        }
    }
}
