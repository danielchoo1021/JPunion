<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subCategory = [
            [
                'sub_category_name'=>'0',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'100',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'125',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'150',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'175',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'200',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'250',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'300',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'350',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'400',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'425',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'450',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'550',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'sub_category_name'=>'700',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ]

        ];
        DB::table('sub_categories')->insert($subCategory);
    }
}