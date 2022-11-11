<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            'group_id' => 29,
            'group_name' => 'Test Group',
            'group_desc' => 'Test description',
            'public' => 1,
            'subject_id' => 1,
            'user_id' => 495
        ]);
    }
}
