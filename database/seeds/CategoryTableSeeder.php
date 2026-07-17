<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = [
            [
                'category_name'=>'Gaming Mouse',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'category_name'=>'Gaming Keyboard',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'category_name'=>"Women's Cloth",
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'category_name'=>"Phone Accessories",
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'category_name'=>"Beauty",
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ]

        ];
        DB::table('categories')->insert($category);
    }
}