<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            [
                'code'=>'AD000001',
                'email'=>'admin@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Admin',
                'l_name'=>'Admin',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ]

        ];
        DB::table('admins')->insert($admins);
    }
}