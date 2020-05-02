<?php

namespace Tests\Unit;

use App\Role;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoleTest extends TestCase
{
    use DatabaseMigrations;

    public function testItHasFourMainRoles()
    {
        $roles = Role::all();

        $this->assertTrue($roles->isNotEmpty());
        $this->assertCount(4, $roles->toArray());
        $this->assertEquals("admin", $roles[0]->name);
        $this->assertEquals("student", $roles[1]->name);
        $this->assertEquals("teacher", $roles[2]->name);
        $this->assertEquals("organizer", $roles[3]->name);
    }

    public function testItBelongsToManyUsers()
    {
        $adminRole = Role::firstWhere('name', 'admin');

        factory(User::class, 4)->create()->each(function($user) {
            $user->roles()->attach(1);
        });

        $this->assertTrue($adminRole->users()->exists());
        $this->assertCount(4, $adminRole->users->toArray());
    }
}
