<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserCanLogin()
    {
        $user = factory(User::class)->create(['email' => "foo@bar.com"]);

        $params = [
            'email' => "foo@bar.com",
            'password' => "password",
        ];

        $response = $this->post('/login', $params);

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function testUserCannotLoginIfCredentialsWrong()
    {
        $params = ['email' => "foo@bar.com", 'password' => "123456789"];

        $response = $this->post('/login', $params, [
            'HTTP_REFERER' => "/login",
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
