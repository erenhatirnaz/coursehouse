<?php

namespace Tests\Unit;

use App\User;
use App\Roles;
use App\Course;
use App\Organizer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OrganizerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreatedEventShouldBeTriggeredWhenOrganizerInsertedToDb()
    {
        $organizer = factory(Organizer::class)->create();

        $this->assertNotEmpty($organizer);
        $this->assertEquals(Roles::ORGANIZER, $organizer->roles->first()->id);
    }

    public function testGlobalScopeShouldBeApplied()
    {
        factory(Organizer::class, 3)->create();
        factory(User::class, 4)->create();

        $this->assertCount(3, Organizer::all()->toArray());
    }

    public function testItShouldBelongsToManyCourses()
    {
        $organizer = factory(Organizer::class)->create();

        $course1 = factory(Course::class)->create();
        $organizer->courses()->attach($course1->id);

        $course2 = factory(Course::class)->create();
        $organizer->courses()->attach($course2->id);

        $this->assertTrue($organizer->courses()->exists());
        $this->assertEquals($course1->id, $organizer->courses[0]->id);
        $this->assertEquals($course2->id, $organizer->courses[1]->id);
    }
}
