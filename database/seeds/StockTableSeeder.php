<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stocks = [
            [
                'product_id'=>'1',
                'type'=>'Increase',
                'quantity'=>'20',
                'remark'=>'Open Stock',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'product_id'=>'2',
                'type'=>'Increase',
                'quantity'=>'30',
                'remark'=>'Open Stock',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'product_id'=>'3',
                'type'=>'Increase',
                'quantity'=>'20',
                'remark'=>'Open Stock',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ]

        ];
        DB::table('stocks')->insert($stocks);
    }
}