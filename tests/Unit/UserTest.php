<?php

namespace Tests\Unit;

use App\User;
use App\Roles;
use Tests\TestCase;
use App\Notifications\VerifyEmail;
use App\Notifications\ResetPasswordEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testItBelongsToManyRoles()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Roles::ADMIN);
        $user->roles()->attach(roles::STUDENT);

        $this->assertTrue($user->roles()->exists());
        $this->assertCount(2, $user->roles->toArray());
    }

    public function testHasRoleFunctionShouldReturnTrueIfUserHasRole()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Roles::ADMIN);

        $this->assertTrue($user->hasRole('admin'));
    }

    public function testHasRoleFunctionShouldReturnFalseIfUserHasntRole()
    {
        $user = factory(User::class)->create();

        $this->assertFalse($user->hasRole('admin'));
    }

    public function testItShouldSentVerificationEmail()
    {
        Notification::fake();

        $user = factory(User::class)->create();
        $user->sendEmailVerificationNotification();

        Notification::assertSentTo([$user], VerifyEmail::class);
    }

    public function testItShouldSentPasswordResetEmail()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $this->post('/password/email', ['email' => $user->email]);

        Notification::assertSentTo([$user], ResetPasswordEmail::class);
    }

    public function testFullNameAttributeShouldConcatNameAndSurname()
    {
        $user = factory(User::class)->create([
            'name' => "Foo",
            "surname" => "Bar",
        ]);

        $this->assertNotNull($user->full_name);
        $this->assertEquals("Foo Bar", $user->full_name);
    }

    public function testItShouldHasProfilePhotoAttribute()
    {
        $userId = factory(User::class)->create(['profile_photo_path' => "foobar.jpg"])->id;

        $user = User::find($userId);

        $this->assertNotEmpty($user);
        $this->assertNotNull($user->profile_photo);
        $this->assertStringContainsString("img/foobar.jpg", $user->profile_photo);
    }
}
