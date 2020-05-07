<?php

namespace Tests\Unit;

use App\Course;
use App\Teacher;
use App\ClassRoom;
use App\Organizer;
use Tests\TestCase;
use App\CourseCategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CourseTest extends TestCase
{
    use DatabaseMigrations;

    public function testItShouldBelongsToCourseCategory()
    {
        $course_category = factory(CourseCategory::class)->create();
        $course = factory(Course::class)->create([
            'course_category_id' => $course_category->id,
        ]);

        $this->assertTrue($course->category()->exists());
    }

    public function testItShouldBelongsToManyTeachers()
    {
        $teachers = factory(Teacher::class, 2)->create();
        $course = factory(Course::class)->create();

        $course->teachers()->attach($teachers[0]->id);
        $course->teachers()->attach($teachers[1]->id);

        $this->assertTrue($course->teachers()->exists());
        $this->assertCount(2, $course->teachers->toArray());
    }

    public function testItShouldBelongsToManyOrganizers()
    {
        $organizers = factory(Organizer::class, 2)->create();
        $course = factory(Course::class)->create();

        $course->organizers()->attach($organizers[0]->id);
        $course->organizers()->attach($organizers[1]->id);

        $this->assertTrue($course->organizers()->exists());
        $this->assertCount(2, $course->organizers->toArray());
    }

    public function testItShoulHasManyClassRooms()
    {
        $course = factory(Course::class)->create();
        factory(ClassRoom::class, 4)->create(['course_id' => $course->id]);

        $this->assertTrue($course->classRooms()->exists());
        $this->assertCount(4, $course->classRooms->toArray());
    }
}
