<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'code'=>'Mb000001',
                'email'=>'sonezack5577@gmail.com',
                'password'=>'$2y$10$tz6DiyMYMr5G6T801vjE0e6MkiQaxEbm16dmAYrCWhXrqDMPuyfc6',
                'f_name'=>'Zack',
                'l_name'=>'Teoh',
                'gender'=>'',
                'dob'=>'',
                'phone'=>'',
                'status'=>'1',
                'created_at'=> \Carbon\Carbon::now()
            ]

        ];
        DB::table('users')->insert($users);
    }
}