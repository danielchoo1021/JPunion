<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = [
            [
                'product_id'=>'1',
                'image'=>'uploads/1/be6e02a9fe32fb8f0ac50364d782f79f.jpg',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'product_id'=>'2',
                'image'=>'uploads/2/f44fc62f1e9d1d33aaa10f0d31cb5098.jpg',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'product_id'=>'3',
                'image'=>'uploads/3/uploads/3/8aa43dc90068f917b4479ce4cb012b73.jpg',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'product_id'=>'3',
                'image'=>'uploads/3/824958957b0800f0c53bd9cb8f2183f9.jpg',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'product_id'=>'3',
                'image'=>'uploads/3/d081beb7632fb0aa3f8d94584b375c4d.jpg',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'product_id'=>'3',
                'image'=>'uploads/3/ceb587546870c264e454537cc5a29aa6.jpg',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ]

        ];
        DB::table('product_images')->insert($images);
    }
}