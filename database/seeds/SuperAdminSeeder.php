<?php

use App\Models\ExceptionLog;
use App\Models\UserDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuperAdminSeeder extends Seeder
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

           $adminUsers = [
               [
                   'id'        => 1,
                   'email'     => 'admin@grs.com',
                   'password'  => bcrypt('123456'),
                   'user_type' => 'admin'
               ]
           ];

           User::where('id', 1)->forceDelete();
           User::insert($adminUsers);

           $adminUserDetails = [
               [
                   'id'             => 1,
                   'user_id'        => 1,
                   'first_name'     => 'GRS',
                   'last_name'      => 'Admin',
                   'full_name'      => 'GRS Admin',
                   'short_name'     => 'SA',
                   'position'       => 'Super Admin',
                   'avatar'         => '',
                   'phone_number'   => '971 1234567',
               ]
           ];
           UserDetail::where('id', 1)->forceDelete();
           UserDetail::insert($adminUserDetails);

       } catch (Exception $e) {
           DB::rollBack();
           ExceptionLog::log($e, static::class);
       } finally {
           DB::commit();
       }
    }
}
