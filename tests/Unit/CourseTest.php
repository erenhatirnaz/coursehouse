<?php

namespace Tests\Unit;

use App\Course;
use App\Teacher;
use App\Student;
use App\ClassRoom;
use App\Organizer;
use Tests\TestCase;
use App\Announcement;
use App\CourseStatus;
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

    public function testLinkAttributeShouldReturnRoute()
    {
        $course = factory(Course::class)->create([
            'name' => 'foobar',
            'slug' => 'foobar'
        ]);

        $this->assertStringContainsString("/course/foobar", $course->link);
    }

    public function testImageAttributeShouldReturnLocalPathIfNotStartsWithHttp()
    {
        $course = factory(Course::class)->create([
            "image_path" => "foobar.png"
        ]);

        $this->assertStringContainsString("/img/courses/foobar.png", $course->image);
    }

    public function testImageAttributeShouldReturnRemoteUrlIfStartsWithHttp()
    {
        $course = factory(Course::class)->create();

        $this->assertStringStartsWith("https://", $course->image);
    }

    public function testItShouldBeReturnedWithClassRoomsCount()
    {
        $courseId = factory(Course::class)->create()->id;
        factory(ClassRoom::class, 4)->create(['course_id' => $courseId]);

        $course = Course::find($courseId);
        $this->assertNotEmpty($course);
        $this->assertArrayHasKey("class_rooms_count", $course->toArray());
        $this->assertEquals(4, $course->class_rooms_count);
    }

    public function testStatusAttributeShouldBeActiveWhenCourseHasAnyClassRoom()
    {
        $courseId = factory(Course::class)->create()->id;
        factory(ClassRoom::class)->create(['course_id' => $courseId]);

        $course = Course::find($courseId);
        $this->assertEquals(CourseStatus::ACTIVE, $course->status);
    }

    public function testStatusAttributeShouldBePassiveWhenCourseHasntAnyClassRoom()
    {
        $course = factory(Course::class)->create();

        $this->assertEquals(CourseStatus::PASSIVE, $course->status);
    }

    public function testItShouldHasDescriptionSummaryAttribute()
    {
        $course = factory(Course::class)->make([
            'description' => "foo bar baz foo bar baz foo bar baz foo bar baz foo bar baz foo bar baz"
        ]);

        $this->assertNotEmpty($course->description_summary);
        $this->assertStringEndsWith("(...)", $course->description_summary);
    }

    public function testItShouldHasGoToAnnouncementsLinkAttribute()
    {
        $course = factory(Course::class)->make();

        $this->assertNotEmpty($course);
        $this->assertNotNull($course->go_to_announcements_link);
        $this->assertStringContainsString(
            "/announcement/explorer?course={$course->slug}",
            $course->go_to_announcements_link
        );
    }

    public function testItShouldHasStudentsCountAttribute()
    {
        $course = factory(Course::class)->create();
        $classRoomId = factory(ClassRoom::class)->create(['course_id' => $course->id]);
        factory(Student::class, 4)->create()->each(function ($student) use ($classRoomId) {
            $student->classRooms()->attach($classRoomId);
        });

        $this->assertNotEmpty($course);
        $this->assertNotNull($course->students_count);
        $this->assertEquals(4, $course->students_count);
    }

    public function testItShouldHasAnnouncementsCountAttribute()
    {
        $course = factory(Course::class)->create();
        $classRoomId = factory(ClassRoom::class)->create(['course_id' => $course->id]);
        factory(Announcement::class, 5)->create([ 'class_room_id' => $classRoomId ]);

        $this->assertNotEmpty($course);
        $this->assertNotNull($course->announcements_count);
        $this->assertEquals(5, $course->announcements_count);
    }
}
