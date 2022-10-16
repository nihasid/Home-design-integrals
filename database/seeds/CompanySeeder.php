<?php

use App\Models\ExceptionLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class CompanySeeder extends Seeder
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

            $companies = [
                [
                    'id' => 1,
                    'company_name' => 'SamSung HQ'
                ],
                [
                    'id' => 2,
                    'company_name' => 'Samsung Asia'
                ],
                [
                    'id' => 3,
                    'company_name' => 'Cheil HQ'
                ],
                [
                    'id' => 4,
                    'company_name' => 'Chiel Midle East'
                ]
            ];
            Company::truncate();
            $companiesTable = new Company();
            $companiesTable->insert($companies);

//            ### Company User Association ###
            $userCompanyMapping = [
                [
                    'id' => 1,
                    'user_id' => 2,
                    'company_id' => 1,
                ],
                [
                    'id' => 2,
                    'user_id' => 3,
                    'company_id' => 3,
                ]
            ];

            DB::table('map_users_companies')->truncate();
            DB::table('map_users_companies')->insert($userCompanyMapping);
        } catch (Exception $e) {
            DB::rollBack();
            ExceptionLog::log($e, static::class);
        } finally {
            DB::commit();
        }
    }
}
