<?php

namespace Tests\Unit;

use App\Organizer;
use App\User;
use App\Roles;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OrganizerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreatedEventShouldBeTriggeredWhenOrganizerInsertedToDb()
    {
        $organizer = factory(Organizer::class, 1)->create()->first();

        $this->assertNotEmpty($organizer);
        $this->assertEquals(Roles::ORGANIZER, $organizer->roles->first()->id);
    }

    public function testGlobalScopeShouldBeApplied()
    {
        factory(Organizer::class, 3)->create();
        factory(User::class, 4)->create();

        $this->assertCount(3, Organizer::all()->toArray());
    }
}
