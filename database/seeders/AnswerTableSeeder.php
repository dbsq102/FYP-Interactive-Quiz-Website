<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnswerTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('answer_bank')->insert([
            'ans_id' => 200,
            'ques_id' => 200,
            'correct' => 1,
            'answer' => 'This is a test correct answer',
            'ans_no' => 1
        ]);
        DB::table('answer_bank')->insert([
            'ans_id' => 201,
            'ques_id' => 200,
            'correct' => 0,
            'answer' => 'This is a test answer',
            'ans_no' => 2
        ]);
        DB::table('answer_bank')->insert([
            'ans_id' => 202,
            'ques_id' => 200,
            'correct' => 0,
            'answer' => 'This is a test answer',
            'ans_no' => 3
        ]);
        DB::table('answer_bank')->insert([
            'ans_id' => 203,
            'ques_id' => 200,
            'correct' => 0,
            'answer' => 'This is a test answer',
            'ans_no' => 4
        ]);
    }
}
