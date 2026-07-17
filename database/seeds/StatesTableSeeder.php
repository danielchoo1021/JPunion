<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $state = [
            [
                'name'=>'WP Kuala Lumpur',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Johor',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Kedah',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Kelantan',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Melaka',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Negeri Sembilan',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Pahang',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Penang',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Perak',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Perlis',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Sabah',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Sarawak',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Selangor',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Terengganu',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Wp Labuan',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'name'=>'Wp Putrajaya',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ]

        ];
        DB::table('states')->insert($state);
    }
}