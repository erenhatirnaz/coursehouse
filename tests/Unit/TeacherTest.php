<?php

namespace Tests\Unit;

use App\User;
use App\Roles;
use App\Course;
use App\Teacher;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeacherTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreatedEventShouldBeTriggeredWhenTeacherInsertedToDb()
    {
        $teacher = factory(Teacher::class)->create();

        $this->assertNotEmpty($teacher);
        $this->assertEquals(Roles::TEACHER, $teacher->roles->first()->id);
    }

    public function testGlobalScopeShouldBeApplied()
    {
        factory(Teacher::class, 3)->create();
        factory(User::class, 4)->create();

        $this->assertCount(3, Teacher::all()->toArray());
    }

    public function testItShouldBelongsToManyCourse()
    {
        $teacher = factory(Teacher::class)->create();

        $course1 = factory(Course::class)->create();
        $teacher->courses()->attach($course1->id);

        $course2 = factory(Course::class)->create();
        $teacher->courses()->attach($course2->id);

        $this->assertTrue($teacher->courses()->exists());
        $this->assertEquals($course1->id, $teacher->courses[0]->id);
        $this->assertEquals($course2->id, $teacher->courses[1]->id);
    }
}
