<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            [
                'bank_name'=>'MAYBANK',
                'bank_code'=>'10000406',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'bank_name'=>'CIMB',
                'bank_code'=>'10000408',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'bank_name'=>'Public Bank',
                'bank_code'=>'10000407',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'bank_name'=>'RHB',
                'bank_code'=>'10000409',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'bank_name'=>'Hong Leong',
                'bank_code'=>'10000410',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'bank_name'=>'BSN',
                'bank_code'=>'10000411',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],

        ];
        DB::table('banks')->insert($banks);
    }
}