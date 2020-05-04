<?php

namespace Tests\Unit;

use App\Admin;
use App\User;
use App\Roles;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreatedEventShouldBeTriggeredWhenAdminInsertedToDb()
    {
        $admin = factory(Admin::class, 1)->create()->first();

        $this->assertNotEmpty($admin);
        $this->assertEquals(Roles::ADMIN, $admin->roles->first()->id);
    }

    public function testGlobalScopeShouldBeApplied()
    {
        factory(Admin::class, 3)->create();
        factory(User::class, 4)->create();

        $this->assertCount(3, Admin::all()->toArray());
    }
}
