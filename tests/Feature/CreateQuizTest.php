<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class CreateQuizTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test Create Quiz View
     * 
     * @return void
     */
    public function test_create_quiz_view() {
        $user = User::factory(User::class)->create();
        $response = $this->actingAs($user)->get(route('createquiz'));

        $response->assertStatus(200);
        $response->assertViewIs('createquiz');
    }
    /**
     * Test Create a new Quiz
     * @dataProvider addQuizDataProvider
     * @return void
     */
    public function test_create_a_new_quiz($quiz_title, $quiz_desc, $gamemode_id, $time_limit, $group_id, $subject_id) {
        $user = User::factory(User::class)->create();
        $response = $this->actingAs($user)->from(route('createquiz'))->post(route('addquiz'), [
            'quiz_title' => $quiz_title,
            'quiz_desc' => $quiz_desc,
            'gamemode_id' => $gamemode_id,
            'time_limit' => $time_limit, 
            'group_id' => $group_id, 
            'subject_id' => $subject_id,
            'user_id' => $user->user_id
        ]);
        $this->assertDatabaseHas('quiz', [
            'quiz_title' => $quiz_title,
            'quiz_summary' => $quiz_desc,
            'gamemode_id' => $gamemode_id,
            'time_limit' => $time_limit, 
            'group_id' => $group_id, 
            'subject_id' => $subject_id, 
            'user_id' => $user->user_id
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('add-quiz-view'));
    }
    public function addQuizDataProvider() {
        return array(
            array("Simple Quiz", "Test description", 1, 0, NULL, 1),
        );
    }
}