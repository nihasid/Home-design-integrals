<?php

use App\Models\ExceptionLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class CompanyUserMappingSeeder extends Seeder
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
        } catch (Exception $e) {
            DB::rollBack();
            ExceptionLog::log($e, static::class);
        } finally {
            DB::commit();
        }
    }
}
