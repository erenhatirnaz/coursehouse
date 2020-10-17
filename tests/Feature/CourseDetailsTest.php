<?php

namespace Tests\Feature;

use App\Course;
use App\Teacher;
use App\Student;
use App\Organizer;
use App\ClassRoom;
use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function testPageShouldReturnHttpOkCode()
    {
        $course = factory(Course::class)->create();

        $response = $this->get("/course/{$course->slug}");

        $response->assertStatus(200);
    }

    public function testPageShouldReturnHttpNotFoundCodeIfGivenSlugIsNotExists()
    {
        $response = $this->get("/course/foo-bar-baz-thing");

        $response->assertStatus(404);
    }

    public function testPageShouldContainsCourseDetails()
    {
        $course = factory(Course::class)->create(['name' => "foo bar course"]);

        $response = $this->get("/course/{$course->slug}");

        $response->assertStatus(200);
        $response->assertSeeText($course->name);
        $response->assertSeeTextInOrder(["Category:", $course->category->name]);
        $response->assertSeeTextInOrder(["Total Student Count:", "0"]);
        $response->assertSeeTextInOrder(["Status:", "Passive"]);
        $response->assertSeeTextInOrder(["Created At:", $course->created_at->diffForHumans()]);

        $response->assertSeeText($course->details);
    }

    public function testPageShouldShowTotalStudentCount()
    {
        $course = factory(Course::class)->create();
        $classRooms = factory(ClassRoom::class, 2)->create([ 'course_id' => $course->id ]);

        foreach ($classRooms as $classRoom) {
            factory(Student::class, 3)->create()->each(function ($student) use ($classRoom) {
                $student->classRooms()->attach($classRoom->id);
            });
        }

        $response = $this->get("/course/{$course->slug}");

        $response->assertStatus(200);
        $response->assertSeeTextInOrder([ "Total Student Count:", "6" ]);
    }

    public function testPageShouldContainsAllTeachersName()
    {
        $course = factory(Course::class)->create();
        $teachers = factory(Teacher::class, 3)->create()->each(function ($teacher) use ($course) {
            $teacher->courses()->attach($course->id);
        });

        $response = $this->get("/course/{$course->slug}");

        $response->assertStatus(200);
        $response->assertSeeTextInOrder(["Teachers", "3"]);
        foreach ($teachers as $teacher) {
            $response->assertSeeText($teacher->full_name);
        }
    }

    public function testPageShouldContainsAllClassRooms()
    {
        $course = factory(Course::class)->create();
        $classRooms = factory(ClassRoom::class, 5)->create(['course_id' => $course->id]);

        $response = $this->get("/course/{$course->slug}");

        $response->assertStatus(200);
        $response->assertSeeTextInOrder(["Status:", "Active"]);
        $response->assertSeeTextInOrder(["Class Rooms", "5"]);
        foreach ($classRooms as $classRoom) {
            $response->assertSeeText($classRoom->name);
        }
    }

    public function testPageShouldContainsAllOrganizers()
    {
        $course = factory(Course::class)->create();
        $organizers = factory(Organizer::class, 2)->create()->each(function ($organizer) use ($course) {
            $organizer->courses()->attach($course->id);
        });

        $response = $this->get("/course/{$course->slug}");

        $response->assertStatus(200);
        $response->assertSeeTextInOrder(["Organizers", "2"]);
        foreach ($organizers as $organizer) {
            $response->assertSeeTextInOrder([ $organizer->full_name, "{$organizer->phone_no}", $organizer->email ]);
        }
    }
}
