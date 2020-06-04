<?php

namespace Tests\Feature;

use App\Student;
use Tests\TestCase;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    public function testStudentCanRegister()
    {
        Notification::fake();

        $params = [
            'name' => "John",
            'surname' => "Doe",
            'email' => "john.mike@doe.com",
            'password' => "stdnt1234",
            'password_confirmation' => "stdnt1234",
            'phone_no' => "123-456-7891",
            'birth_date' => "1985-01-01",
        ];

        $response = $this->post('/register', $params);

        $response->assertStatus(302);
        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', ['email' => "john.mike@doe.com"]);
    }

    public function testStudentCannotRegiterTwice()
    {
        Notification::fake();

        $user = factory(Student::class)->create();

        $params = [
            'email' => $user->email,
            'phone_no' => $user->phone_no,
            'name' => "John",
            'surname' => "Doe",
            'password' => "stdnt1234",
            'password_confirmation' => "stdnt1234",
            'birth_date' => "1985-01-01",
        ];

        $response = $this->post('/register', $params, [
            'HTTP_REFERER' => '/register',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/register');
    }
}
