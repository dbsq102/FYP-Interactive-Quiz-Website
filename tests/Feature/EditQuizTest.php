<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\QuizTableSeeder;
use Database\Seeders\QuestionTableSeeder;
use Database\Seeders\UserTableSeeder;
use Database\Seeders\AnswerTableSeeder;

class EditQuizTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test Edit Quiz View
     * 
     * @return void
     */
    public function test_edit_quiz_view() {
        $user = User::factory(User::class)->create();
        $testQuizID = 10; //One of the existing quizzes
        $response = $this->actingAs($user)->get(route('editquiz', $testQuizID));

        $response->assertStatus(200);
        $response->assertViewIs('quizeditor');
    }
    /**
     * Test Update Quiz Settings
     * 
     * @dataProvider updateQuizDataProvider
     * @return void
     */
    public function test_update_quiz_settings($quiz_title, $quiz_desc, $gamemode_id, $time_limit, $group_id, $subject_id) {
        //Test quiz ID is 200
        $this->seed(UserTableSeeder::class);
        $this->seed(QuizTableSeeder::class);
        $this->withSession(['quizID' => 200]);
        $user = User::factory(User::class)->create();
        $response = $this->actingAs($user)->from(route('editquiz', 200))->post(route('updatequiz'), [
            'update_quiz_title' => $quiz_title,
            'update_quiz_desc' => $quiz_desc,
            'update_gamemode_id' => $gamemode_id,
            'update_time_limit' => $time_limit, 
            'update_group_id' => $group_id, 
            'update_subject_id' => $subject_id
        ]);
        $this->assertDatabaseHas('quiz', [
            'quiz_title' => $quiz_title,
            'quiz_summary' => $quiz_desc,
            'gamemode_id' => $gamemode_id,
            'time_limit' => $time_limit, 
            'group_id' => $group_id, 
            'subject_id' => $subject_id
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('editquiz', 200));
    }
    public function updateQuizDataProvider() {
        return array(
            array("Simple Quiz Editted", "Test description", 1, 30, NULL, 1),
        );
    }
    /**
     * Test Update Question Type
     * 
     * @return void
     */
    public function test_update_question_type() {
        $this->seed(UserTableSeeder::class);
        $this->seed(QuizTableSeeder::class);
        $this->seed(QuestionTableSeeder::class);
        $user = User::factory(User::class)->create();
        //Test Question No. is 1
        $this->withSession(['quesNo' => 1]);
        //Test Quiz ID is 200
        $this->withSession(['quizID' => 200]);

        $response = $this->actingAs($user)->from('quizeditor')->post('/update-ques-type', [
            'question_type' => 2,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('editquiz', 200));
    }
    /**
     * Test Save Question
     * 
     * @return void
     */
    /*public function test_save_question() {
        $this->seed(UserTableSeeder::class);
        $this->seed(QuizTableSeeder::class);
        $this->seed(QuestionTableSeeder::class);
        $this->seed(AnswerTableSeeder::class);
        $user = User::factory(User::class)->create();
        
        //Test Question No. is 1
        $this->withSession(['quesNo' => 1]);
        //Test Quiz ID is 200
        $this->withSession(['quizID' => 200]);
        //Test Question ID is 200
        $this->withSession(['quesID' => 200]);

        $response = $this->actingAs($user)->from('editquiz')->get('/save-multi-choice', [
            'question_title' => 'This is a question',
            'answer1' => 'Hi',
            'answer2' => 'This',
            'answer3' => 'Is',
            'answer4' => 'An',
            'answer5' => 'Answer',
            'answer6' => 'That',
            'answer7' => 'Is',
            'answer8' => 'Used',
            'answer9' => 'For Testing',
            'correct' => 4
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('question_bank', [
            'question'=> $question,
        ]);
    }*/
    /**
     * Test Delete Question
     * 
     * @return void
     */
    public function test_delete_question() {
        $this->seed(UserTableSeeder::class);
        $this->seed(QuizTableSeeder::class);
        $this->seed(QuestionTableSeeder::class);
        $user = User::factory(User::class)->create();
        //Test Question No. is 2
        $this->withSession(['quesNo' => 2]);
        //Test Quiz ID is 200
        $this->withSession(['quizID' => 200]);

        $response = $this->actingAs($user)->from('quizeditor')->post('/delete-question');

        $response->assertStatus(302);
        $response->assertRedirect(route('editquiz', 200));
    }
}