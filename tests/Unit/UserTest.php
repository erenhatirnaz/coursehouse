<?php

namespace Tests\Unit;

use App\User;
use App\Roles;
use Tests\TestCase;
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
}
