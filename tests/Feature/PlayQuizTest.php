<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class PlayQuizTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test Standby Page View
     * 
     * @return void
     */
    public function test_standby_view() {
        $user = User::factory(User::class)->create();
        $testQuizID = 10; //One of the existing quizzes
        $response = $this->actingAs($user)->get(route('standby', $testQuizID));

        $response->assertStatus(200);
        $response->assertViewIs('standby');
    }
    /**
     * Test Play Quiz Page View
     * 
     * @return void
     */
    public function test_play_quiz_view() {
        $user = User::factory(User::class)->create();
        $this->withSession(['playQuesNo' => 1]);
        $testQuizID = 10; //One of the existing quizzes
        $response = $this->actingAs($user)->get(route('play-quiz', $testQuizID));

        $response->assertStatus(200);
        $response->assertViewIs('playquiz');
    }
    /**
     * Test Check Answer
     * 
     * @return void
     */
    public function test_check_answer() {
        $user = User::factory(User::class)->create();
        $this->withSession(['playQuesNo' => 1]);
        $this->withSession(['score', 0]);
        $response = $this->actingAs($user)->get(route('check-answer', 1)); //Assume question is correct

        $response->assertStatus(302);
        $response->assertRedirect('finish-quiz');
    }
    /**
     * Test Save Attempt
     * 
     * @return void
     */
    public function test_save_attempt() {
        $user = User::factory(User::class)->create();
        $this->withSession(['playQuizId' => 10]); //Test quiz ID
        $this->withSession(['score'=> 3]);
        $response = $this->actingAs($user)->get(route('finish-quiz'));

        $response->assertStatus(302);
        $response->assertRedirect('home');
    }
}