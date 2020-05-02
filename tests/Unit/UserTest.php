<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testItBelongsToManyRoles()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(1); // admin
        $user->roles()->attach(2); // student

        $this->assertTrue($user->roles()->exists());
        $this->assertCount(2, $user->roles->toArray());
    }

    public function testHasRoleFunctionShouldReturnTrueIfUserHasRole()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(1); // admin

        $this->assertTrue($user->hasRole('admin'));
    }

    public function testHasRoleFunctionShouldReturnFalseIfUserHasntRole()
    {
        $user = factory(User::class)->create();

        $this->assertFalse($user->hasRole('admin'));
    }
}
