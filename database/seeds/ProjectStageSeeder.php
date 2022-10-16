<?php

use App\Models\ExceptionLog;
use App\Models\Stage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectStageSeeder extends Seeder
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

            $stages = [
                [
                    'name' => 'Project kick-off',
                    'working_days' => 0,
                    'start_after_days' => 0,
                    'order' => 1
                ],
                [
                    'name' => 'Concept Design',
                    'working_days' => 28,
                    'start_after_days' => 0,
                    'order' => 2,
                    'children' => [
                        [
                            'name' => 'Layout sketch & floor plan',
                            'working_days' => 9,
                            'start_after_days' => 0,
                            'order' => 1
                        ],
                        [
                            'name' => 'Schematic Design',
                            'working_days' => 9,
                            'start_after_days' => 9,
                            'order' => 2
                        ],
                        [
                            'name' => '3D renderings',
                            'working_days' => 10,
                            'start_after_days' => 18,
                            'order' => 3
                        ],
                    ]
                ],
                [
                    'name' => 'Technical drawings',
                    'working_days' => 8,
                    'start_after_days' => 28,
                    'order' => 3
                ],
                [
                    'name' => 'Approval',
                    'working_days' => 7,
                    'start_after_days' => 36,
                    'order' => 4
                ],
                [
                    'name' => 'Construction',
                    'working_days' => 37,
                    'start_after_days' => 36,
                    'order' => 5,
                    'children' => [
                        [
                            'name' => 'Infra',
                            'working_days' => 29,
                            'start_after_days' => 36,
                            'order' => 1,
                            'children' => [
                                [
                                    'name' => 'Temporary work / Hoarding installation / Demolition',
                                    'working_days' => 7,
                                    'start_after_days' => 36,
                                    'order' => 1
                                ],
                                [
                                    'name' => 'Site measurement',
                                    'working_days' => 2,
                                    'start_after_days' => 36,
                                    'order' => 2
                                ],
                                [
                                    'name' => '[M] HVAC Duct installation',
                                    'working_days' => 4,
                                    'start_after_days' => 38,
                                    'order' => 3
                                ],
                                [
                                    'name' => '[M] Chamber & Diffuser installation',
                                    'working_days' => 3,
                                    'start_after_days' => 42,
                                    'order' => 4
                                ],
                                [
                                    'name' => '[E] Ceiling electric wiring',
                                    'working_days' => 5,
                                    'start_after_days' => 45,
                                    'order' => 5
                                ],
                                [
                                    'name' => '[T] Ceiling data infrastructure',
                                    'working_days' => 6,
                                    'start_after_days' => 45,
                                    'order' => 6
                                ],
                                [
                                    'name' => '[F] Fire fighting system (Sprinkler)',
                                    'working_days' => 7,
                                    'start_after_days' => 45,
                                    'order' => 7
                                ],
                                [
                                    'name' => '[F] Fire alarm & Smoke Detection',
                                    'working_days' => 6,
                                    'start_after_days' => 45,
                                    'order' => 8
                                ],
                                [
                                    'name' => '[E] Wall electric wiring',
                                    'working_days' => 6,
                                    'start_after_days' => 51,
                                    'order' => 9
                                ],
                                [
                                    'name' => '[T] Wall data infrastructure',
                                    'working_days' => 4,
                                    'start_after_days' => 57,
                                    'order' => 10
                                ],
                                [
                                    'name' => '[E] Floor electric wiring',
                                    'working_days' => 8,
                                    'start_after_days' => 57,
                                    'order' => 11
                                ],
                            ]
                        ],
                        [
                            'name' => 'Floor',
                            'working_days' => 10,
                            'start_after_days' => 50,
                            'order' => 2,
                            'children' => [
                                [
                                    'name' => 'Floor leveling (+Electrical piping)',
                                    'working_days' => 5,
                                    'start_after_days' => 50,
                                    'order' => 1
                                ],
                                [
                                    'name' => 'FOH (Sales area) floor finishing',
                                    'working_days' => 4,
                                    'start_after_days' => 55,
                                    'order' => 2
                                ],
                                [
                                    'name' => 'Store front (Entrance) floor finishing',
                                    'working_days' => 4,
                                    'start_after_days' => 55,
                                    'order' => 3
                                ],
                                [
                                    'name' => 'BOH (Staff area) floor finishing',
                                    'working_days' => 2,
                                    'start_after_days' => 58,
                                    'order' => 4
                                ],
                            ]
                        ],
                        [
                            'name' => 'Ceiling',
                            'working_days' => 14,
                            'start_after_days' => 50,
                            'order' => 3,
                            'children' => [
                                [
                                    'name' => 'Ceiling Structure & Gypsum Board',
                                    'working_days' => 6,
                                    'start_after_days' => 50,
                                    'order' => 1
                                ],
                                [
                                    'name' => 'Light & Fire fixture installation',
                                    'working_days' => 2,
                                    'start_after_days' => 56,
                                    'order' => 2
                                ],
                                [
                                    'name' => 'Ceiling finishing (Paint)',
                                    'working_days' => 4,
                                    'start_after_days' => 50,
                                    'order' => 3
                                ],
                            ]
                        ],
                        [
                            'name' => 'Wall',
                            'working_days' => 13,
                            'start_after_days' => 50,
                            'order' => 4,
                            'children' => [
                                [
                                    'name' => 'Wooden frame work (+Gypsum board)',
                                    'working_days' => 3,
                                    'start_after_days' => 50,
                                    'order' => 1
                                ],
                                [
                                    'name' => 'Wall paint finishing',
                                    'working_days' => 3,
                                    'start_after_days' => 51,
                                    'order' => 2
                                ],
                                [
                                    'name' => 'Concrete panel covering & Touch up',
                                    'working_days' => 1,
                                    'start_after_days' => 50,
                                    'order' => 3
                                ],
                            ]
                        ],
                        [
                            'name' => 'Storefront',
                            'working_days' => 8,
                            'start_after_days' => 57,
                            'order' => 5,
                            'children' => [
                                [
                                    'name' => 'Security rolling shutter',
                                    'working_days' => 2,
                                    'start_after_days' => 57,
                                    'order' => 1
                                ],
                                [
                                    'name' => 'Façade framing structure',
                                    'working_days' => 7,
                                    'start_after_days' => 58,
                                    'order' => 2
                                ],
                                [
                                    'name' => 'Façade framing structure',
                                    'working_days' => 7,
                                    'start_after_days' => 58,
                                    'order' => 3
                                ],
                                [
                                    'name' => 'Lettermark installation',
                                    'working_days' => 2,
                                    'start_after_days' => 63,
                                    'order' => 4
                                ],
                            ]
                        ],
                        [
                            'name' => 'Fixture',
                            'working_days' => 19,
                            'start_after_days' => 52,
                            'order' => 6,
                            'children' => [
                                [
                                    'name' => 'Fixture delivery',
                                    'working_days' => 2,
                                    'start_after_days' => 52,
                                    'order' => 1
                                ],
                                [
                                    'name' => 'Wall fixture installation',
                                    'working_days' => 7,
                                    'start_after_days' => 54,
                                    'order' => 2
                                ],
                                [
                                    'name' => 'Loose fixture installation',
                                    'working_days' => 4,
                                    'start_after_days' => 67,
                                    'order' => 3
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'name' => 'IT & VMD',
                    'working_days' => 3,
                    'start_after_days' => 68,
                    'order' => 6,
                    'children' => [
                        [
                            'name' => 'IT',
                            'working_days' => 3,
                            'start_after_days' => 68,
                            'order' => 1,
                            'children' => [
                                [
                                    'name' => 'LFD & TV',
                                    'working_days' => 3,
                                    'start_after_days' => 68,
                                    'order' => 1
                                ],
                                [
                                    'name' => 'CCTV / WIFI',
                                    'working_days' => 3,
                                    'start_after_days' => 68,
                                    'order' => 2
                                ],
                            ]
                        ],
                        [
                            'name' => 'VMD (By subs)',
                            'working_days' => 3,
                            'start_after_days' => 68,
                            'order' => 2,
                            'children' => [
                                [
                                    'name' => 'VM Kits',
                                    'working_days' => 3,
                                    'start_after_days' => 68,
                                    'order' => 1
                                ],
                                [
                                    'name' => 'KV & Product Signage',
                                    'working_days' => 3,
                                    'start_after_days' => 68,
                                    'order' => 2
                                ],
                                [
                                    'name' => 'KV & Product Signage',
                                    'working_days' => 3,
                                    'start_after_days' => 68,
                                    'order' => 3
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'name' => 'Completion',
                    'working_days' => 8,
                    'start_after_days' => 68,
                    'order' => 7,
                    'children' => [
                        [
                            'name' => 'Site Check',
                            'working_days' => 2,
                            'start_after_days' => 68,
                            'order' => 1
                        ],
                        [
                            'name' => 'Site Repairment - Snagging Report',
                            'working_days' => 4,
                            'start_after_days' => 68,
                            'order' => 2
                        ],
                        [
                            'name' => 'Site hand over (Store open)',
                            'working_days' => 1,
                            'start_after_days' => 71,
                            'order' => 3
                        ],
                        [
                            'name' => 'Project Closing',
                            'working_days' => 7,
                            'start_after_days' => 69,
                            'order' => 4
                        ],
                    ]
                ]
            ];

            Stage::truncate();
            foreach ($stages as $stage) {
                Stage::create( $stage );
            }

        } catch (Exception $e) {
            DB::rollBack();
            ExceptionLog::log($e, static::class);
        } finally {
            DB::commit();
        }
    }
}
