<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AffiliateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $affiliates = [
            [
                'affiliate_id'=>'M000001',
                'user_id'=>'AD000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000002',
                'user_id'=>'AD000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000003',
                'user_id'=>'AD000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000004',
                'user_id'=>'AD000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000005',
                'user_id'=>'AD000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000006',
                'user_id'=>'AD000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000007',
                'user_id'=>'AD000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000008',
                'user_id'=>'AD000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000009',
                'user_id'=>'AD000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000010',
                'user_id'=>'AD000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000011',
                'user_id'=>'M000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000012',
                'user_id'=>'M000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000013',
                'user_id'=>'M000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000014',
                'user_id'=>'M000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000015',
                'user_id'=>'M000001',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000016',
                'user_id'=>'M000002',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000017',
                'user_id'=>'M000002',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000018',
                'user_id'=>'M000002',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000019',
                'user_id'=>'M000002',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000020',
                'user_id'=>'M000002',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000021',
                'user_id'=>'M000002',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000022',
                'user_id'=>'M000003',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000022',
                'user_id'=>'M000003',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000023',
                'user_id'=>'M000005',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000024',
                'user_id'=>'M000005',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000025',
                'user_id'=>'M000005',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000026',
                'user_id'=>'M000005',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000027',
                'user_id'=>'M000005',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000028',
                'user_id'=>'M000005',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000029',
                'user_id'=>'M000005',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000030',
                'user_id'=>'M000005',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000031',
                'user_id'=>'M000006',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000032',
                'user_id'=>'M000006',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'affiliate_id'=>'M000033',
                'user_id'=>'M000006',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ]

        ];
        DB::table('affiliates')->insert($affiliates);
    }
}