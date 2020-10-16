<?php

namespace Tests\Unit;

use App\Course;
use App\Student;
use App\ClassRoom;
use Tests\TestCase;
use App\Announcement;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ClassRoomTest extends TestCase
{
    use DatabaseMigrations;

    public function testItShouldHasManyOrganizers()
    {
        $classRoom = factory(ClassRoom::class)->create();

        $announcement1 = factory(Announcement::class)->create([
            'class_room_id' => $classRoom->id,
        ]);
        $announcement2 = factory(Announcement::class)->create([
            'class_room_id' => $classRoom->id,
        ]);

        $this->assertTrue($classRoom->announcements()->exists());
    }

    public function testItShouldHasManyStudents()
    {
        $classRoom = factory(ClassRoom::class)->create();

        $students = factory(Student::class, 2)->create();
        $students[0]->classRooms()->attach($classRoom->id);
        $students[1]->classRooms()->attach($classRoom->id);

        $this->assertTrue($classRoom->students()->exists());
        $this->assertNotEmpty($classRoom->students()->find($students[0]->id));
        $this->assertNotEmpty($classRoom->students()->find($students[1]->id));
    }

    public function testItShouldBelongsToCourse()
    {
        $course = factory(Course::class)->create();
        $classRoom = factory(ClassRoom::class)->create([
            'course_id' => $course->id,
        ]);

        $this->assertTrue($classRoom->course()->exists());
        $this->assertEquals($course->id, $classRoom->course->id);
    }

    public function testItShouldHasLinkAttribute()
    {
        $course = factory(Course::class)->create();
        $classRoom = factory(ClassRoom::class)->create([ 'course_id' => $course->id ]);

        $this->assertNotEmpty($classRoom);
        $this->assertNotNull($classRoom->link);
        $this->assertStringContainsString("/{$course->slug}/classrooms/{$classRoom->slug}", $classRoom->link);
    }
}
