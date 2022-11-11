<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('question_bank')->insert([
            'ques_id' => 200,
            'quiz_id' => 200,
            'type_id' => 1,
            'question' => 'This is a test question',
            'ques_no' => 1
        ]);
        DB::table('question_bank')->insert([
            'ques_id' => 201,
            'quiz_id' => 200,
            'type_id' => 1,
            'question' => 'This is a test question',
            'ques_no' => 2
        ]);
    }
}
