<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test Register View
     *
     * @return void
     */
    public function test_register_view() {
        
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * Test Register
     * @dataProvider validRegisterDataProvider
     * @return void
     */
    public function test_register($username, $email, $password, $role) {

        $response = $this->from('register')->post('/register', [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'password-confirm' => $password,
            'role' => $role
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'username'=>$username,
            'email'=>$email,
        ]);
    }

    public function validRegisterDataProvider() {
        return array(
            array("TestName", "testemail2@hotmail.com", "Test_p4ss", 1),
            array("Test", "testemail@hotmail.com", "Test_p4ss", 0),
            array("Test Student", "testemail3@hotmail.com", "Test_p4ss", 1),
        );
    }

    /**
     * Test Register Fail
     * @dataProvider invalidRegisterDataProvider
     * @return void
     */
    public function test_register_fail($username, $email, $password, $role) {

        $response = $this->from('register')->post('/register', [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'password-confirm' => $password,
            'role' => $role
        ]);

        $response->assertRedirect('register');
        $response->assertSessionHasErrors();
    }

    public function invalidRegisterDataProvider() {
        return array(
            array("", "", "", 0),
            array("TestName", "NotEmail", "Test_p4ss", 1),
            array("TestName", "testemail@hotmail.com", "wrongpass", 1),
            array("TestName", "testemail@hotmail.com", "Test_p4ss", "Not Number"),
        );
    }
}