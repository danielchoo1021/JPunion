<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchants = [
            [
                'code'=>'M000001',
                'email'=>'merchant1@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'May',
                'l_name'=>'Ng',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000002',
                'email'=>'merchant2@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Alice',
                'l_name'=>'Ang',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000003',
                'email'=>'merchant3@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Melody',
                'l_name'=>'Chan',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000004',
                'email'=>'merchant4@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Jessica',
                'l_name'=>'Jung',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000005',
                'email'=>'merchant5@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Tiffany',
                'l_name'=>'Huang',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000006',
                'email'=>'merchant6@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Jecci',
                'l_name'=>'Low',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000007',
                'email'=>'merchant7@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Alisa',
                'l_name'=>'Lee',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000008',
                'email'=>'merchan8@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Rebecca',
                'l_name'=>'Teoh',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000009',
                'email'=>'merchant9@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'June',
                'l_name'=>'Ong',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000010',
                'email'=>'merchant10@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Janice',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000011',
                'email'=>'merchant11@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Ashley',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000012',
                'email'=>'merchant12@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Vincin',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000013',
                'email'=>'merchant13@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Summer',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000014',
                'email'=>'merchant14@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'梦梦',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000015',
                'email'=>'merchant15@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'JS',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000016',
                'email'=>'merchant16@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Cindy',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000017',
                'email'=>'merchant17gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Joevy',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000018',
                'email'=>'merchant18@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Chloe',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000019',
                'email'=>'merchant19@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Vincy',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000020',
                'email'=>'merchant20@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Gena',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000021',
                'email'=>'merchant21@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Champion',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000022',
                'email'=>'merchant22@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Zeon',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000023',
                'email'=>'merchant23@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Cindy',
                'l_name'=>'Teoh',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000024',
                'email'=>'merchant24@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Stephony',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000025',
                'email'=>'merchant25@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Tiffany',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000026',
                'email'=>'merchant26@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Candy',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000027',
                'email'=>'merchant27@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Jamis',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000028',
                'email'=>'merchant28@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Rebecca',
                'l_name'=>'Yin Yin',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000029',
                'email'=>'merchant29@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Wei Nee',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000030',
                'email'=>'merchant30@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Jia Ying',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000031',
                'email'=>'merchant31@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Cindy',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000032',
                'email'=>'merchant32@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Summer',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ],
            [
                'code'=>'M000033',
                'email'=>'merchant33@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Ashley',
                'l_name'=>'',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ]

        ];
        DB::table('merchants')->insert($merchants);
    }
}