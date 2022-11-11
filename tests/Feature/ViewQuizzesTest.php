<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\QuizTableSeeder;
use Database\Seeders\UserTableSeeder;
use Database\Factories\UserFactory;

class ViewQuizzesTest extends TestCase
{
    
    use DatabaseTransactions;
    /**
     * Test Quizzes View
     *
     * @return void
     */
    public function test_quizzes_view() {
        $user = User::factory(User::class)->create();
        $response = $this->actingAs($user)->get(route('managequiz'));

        $response->assertStatus(200);
        $response->assertViewIs('managequiz');
    }
    /**
     * Test Delete Quiz
     * 
     * @return void
     */
    public function test_delete_quiz() {
        $this->seed(UserTableSeeder::class);
        $this->seed(QuizTableSeeder::class);
        $user = User::factory(User::class)->create();

        // Delete quiz
        $response = $this->actingAs($user)->post(route('delete-quiz', 200));

        $this->assertTrue($response != null);
    }
}