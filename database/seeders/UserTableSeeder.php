<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_id'=> 495,
            'username' => 'Kenny Bode',
            'email' => 'testemail3@hotmail.com',
            'password' => 'Test_p4ss',
            'role' => 1,
            'created_at' => '2022-10-30',
            'updated_at' => '2022-10-30'
        ]);
    }
}
