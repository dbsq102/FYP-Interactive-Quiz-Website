<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class HomeTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test Home Screen View
     *
     * @return void
     */
    public function test_home_view() {
        $user = User::factory(User::class)->create();
        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }
}