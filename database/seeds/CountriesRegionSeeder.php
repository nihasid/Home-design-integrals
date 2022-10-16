<?php

use App\Models\ExceptionLog;
use App\Models\Region;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesRegionSeeder extends Seeder
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


            $regions = [
                [
                    'id' => 1,
                    'name' => 'North America',
                ],
                [
                    'id' => 2,
                    'name' => 'Latin America',
                ],
                [
                    'id' => 3,
                    'name' => 'CIS',
                ],
                [
                    'id' => 4,
                    'name' => 'Europe',
                ],
                [
                    'id' => 5,
                    'name' => 'Africa',
                ], [
                    'id' => 6,
                    'name' => 'Middle East',
                ], [
                    'id' => 7,
                    'name' => 'Asia',
                ]
            ];
            Region::truncate();
            Region::insert($regions);
            $countries = [
                //----------------------North America-------------------------
                [
                    'id' => 1,
                    'region_id' => 1,
                    'name' => 'Canada',
                    'code' => 'ca'
                ],
                [
                    'id' => '2',
                    'region_id' => '1',
                    'name' => 'USA',
                    'code' => 'us'
                ],
                //----------------------Latin America-------------------------
                [
                    'id' => '3',
                    'region_id' => '2',
                    'name' => 'Argentina',
                    'code' => 'ar'
                ],
                [
                    'id' => '4',
                    'region_id' => '2',
                    'name' => 'Brazil',
                    'code' => 'br'
                ],
                [
                    'id' => '5',
                    'region_id' => '2',
                    'name' => 'Chile',
                    'code' => 'cl'
                ],
                [
                    'id' => '6',
                    'region_id' => '2',
                    'name' => 'Colombia',
                    'code' => 'co'
                ],
                [
                    'id' => '7',
                    'region_id' => '2',
                    'name' => 'Guatemala',
                    'code' => 'gt'
                ],
                [
                    'id' => '8',
                    'region_id' => '2',
                    'name' => 'Maxico',
                    'code' => 'mx'
                ],
                [
                    'id' => '9',
                    'region_id' => '2',
                    'name' => 'Peru',
                    'code' => 'pe'
                ],
                [
                    'id' => '10',
                    'region_id' => '2',
                    'name' => 'Panama',
                    'code' => 'pa'
                ],
                //----------------------- CIS -------------------------
                [
                    'id' => '11',
                    'region_id' => '3',
                    'name' => 'Kazakhstan',
                    'code' => 'kz'
                ],
                [
                    'id' => '12',
                    'region_id' => '3',
                    'name' => 'Russia',
                    'code' => 'ru'
                ], [
                    'id' => '13',
                    'region_id' => '3',
                    'name' => 'Ukraine',
                    'code' => 'ua'
                ],
                //----------------------Europe-------------------------
                [
                    'id' => '14',
                    'region_id' => '4',
                    'name' => 'Austria',
                    'code' => 'at'
                ],
                [
                    'id' => '15',
                    'region_id' => '4',
                    'name' => 'Belgium',
                    'code' => 'be'
                ],
                [
                    'id' => '16',
                    'region_id' => '4',
                    'name' => 'Czech',
                    'code' => 'cz'
                ],
                [
                    'id' => '17',
                    'region_id' => '4',
                    'name' => 'France',
                    'code' => 'fr'
                ],
                [
                    'id' => '18',
                    'region_id' => '4',
                    'name' => 'Germany',
                    'code' => 'de'
                ],
                [
                    'id' => '19',
                    'region_id' => '4',
                    'name' => 'Italy',
                    'code' => 'it'
                ],
                [
                    'id' => '20',
                    'region_id' => '4',
                    'name' => 'Nederland',
                    'code' => 'nl'
                ],
                [
                    'id' => '21',
                    'region_id' => '4',
                    'name' => 'Poland',
                    'code' => 'pl'
                ], [
                    'id' => '22',
                    'region_id' => '4',
                    'name' => 'Portugal',
                    'code' => 'pt'
                ], [
                    'id' => '23',
                    'region_id' => '4',
                    'name' => 'Spain',
                    'code' => 'es'
                ],
                [
                    'id' => '24',
                    'region_id' => '4',
                    'name' => 'Sweden',
                    'code' => 'se'
                ],
                [
                    'id' => '25',
                    'region_id' => '4',
                    'name' => 'United Kingdom',
                    'code' => 'gb'
                ],
                //----------------------Africa-------------------------
                [
                    'id' => '26',
                    'region_id' => '5',
                    'name' => 'Ghana',
                    'code' => 'gh'
                ],
                [
                    'id' => '27',
                    'region_id' => '5',
                    'name' => 'Kenya',
                    'code' => 'ke'
                ],
                [
                    'id' => '28',
                    'region_id' => '5',
                    'name' => 'Nigeria',
                    'code' => 'ng'
                ], [
                    'id' => '29',
                    'region_id' => '5',
                    'name' => 'South Africa',
                    'code' => 'za'
                ],
                //----------------------Middle East-------------------------
                [
                    'id' => '30',
                    'region_id' => '6',
                    'name' => 'UAE',
                    'code' => 'ae'
                ],
                [
                    'id' => '31',
                    'region_id' => '6',
                    'name' => 'Turkey',
                    'code' => 'tr'
                ],
                [
                    'id' => '32',
                    'region_id' => '6',
                    'name' => 'Saudi Arabia',
                    'code' => 'sa'
                ],
                [
                    'id' => '33',
                    'region_id' => '6',
                    'name' => 'Jordan',
                    'code' => 'jo'
                ],
                //----------------------Asia-------------------------
                [
                    'id' => '34',
                    'region_id' => '7',
                    'name' => 'China',
                    'code' => 'cn'
                ],
                [
                    'id' => '35',
                    'region_id' => '7',
                    'name' => 'India',
                    'code' => 'in'
                ],
                [
                    'id' => '36',
                    'region_id' => '7',
                    'name' => 'Indonesia',
                    'code' => 'id'
                ], [
                    'id' => '37',
                    'region_id' => '7',
                    'name' => 'Japan',
                    'code' => 'jp'
                ], [
                    'id' => '38',
                    'region_id' => '7',
                    'name' => 'Korea',
                    'code' => 'kr'
                ], [
                    'id' => '39',
                    'region_id' => '7',
                    'name' => 'Malaysia',
                    'code' => 'my'
                ], [
                    'id' => '40',
                    'region_id' => '7',
                    'name' => 'Philippines',
                    'code' => 'ph'
                ], [
                    'id' => '41',
                    'region_id' => '7',
                    'name' => 'Singapore',
                    'code' => 'sg'
                ], [
                    'id' => '42',
                    'region_id' => '7',
                    'name' => 'Thailand',
                    'code' => 'th'
                ],
                [
                    'id' => '43',
                    'region_id' => '7',
                    'name' => 'Vietnam',
                    'code' => 'vn'
                ]
            ];
            Country::truncate();
            Country::insert($countries);
        } catch (Exception $e) {
            DB::rollBack();
            ExceptionLog::log($e, static::class);
        } finally {
            DB::commit();
        }
    }
}
