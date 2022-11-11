<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserTableSeeder;

class LoginTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Test Login View
     *
     * @return void
     */
    public function test_login_view() {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }
    /**
     * Test Login Successful
     * 
     * @return void
     */
    public function test_login() {
        $user = User::factory(User::class)->create();

        $response = $this->actingAs($user)->from('login')->post('/login', [
            'email' => 'testemail3@hotmail.com',
            'password' => 'Test_p4ss',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('home');
    }

    /**
     * Test Login Failed
     * @dataProvider invalidLoginDataProvider
     * @return void
     */
    public function test_login_failed($email, $password) {
        $this->seed(UserTableSeeder::class);

        $response = $this->from('login')->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(302);

        $response->assertRedirect('login');
    }

    public function invalidLoginDataProvider() {
        return array(
            array("testemail2@hotmail.com", "wrongpass"),
            array("wrongemail", "Test_p4ss"),
        );
    }
}