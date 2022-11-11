<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quiz')->insert([
            'quiz_id' => 200,
            'quiz_title' => 'TestQuiz', 
            'quiz_summary' => 'This is a test', 
            'gamemode_id' => '1',
            'time_limit' => '30', 
            'group_id' => NULL, 
            'subject_id' => 1, 
            'user_id' => 495,
        ]);
    }
}
