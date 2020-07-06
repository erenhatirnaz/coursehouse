<?php

namespace Tests\Unit\Repositories;

use App\Course;
use Tests\TestCase;
use App\Repositories\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CourseRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var CourseRepositoryInterface
     */
    private $courses;

    protected function setUp(): void
    {
        parent::setUp();

        $this->courses = $this->app->make(CourseRepositoryInterface::class);
    }

    public function testItShouldReturnAllCourses()
    {
        factory(Course::class, 5)->create();

        $courses = $this->courses->all();

        $this->assertNotEmpty($courses);
        $this->assertCount(5, $courses);
        $this->assertArrayNotHasKey("teachers", $courses[0]->toArray());
    }

    public function testItShouldReturnACourseById()
    {
        $createdCourse = factory(Course::class)->create(["name" => "foo"]);

        $course = $this->courses->show($createdCourse->id);

        $this->assertNotEmpty($course);
        $this->assertEquals("foo", $course->name);
    }

    public function testItShouldThrowNotFoundExceptionIfGivenIdIsNotExists()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Course] 1");

        $this->courses->show(1);
    }

    public function testItShouldReturnAllCoursesWithRelatedEntities()
    {
        factory(Course::class, 5)->create();

        $coursesWithRelations = $this->courses->allWithRelations(["teachers"]);

        $this->assertNotEmpty($coursesWithRelations);
        $this->assertArrayHasKey("teachers", $coursesWithRelations[0]->toArray());
    }

    public function testItShouldBeAbleToCreateACourse()
    {
        $course = new Course();
        $course->name = "foobar";
        $course->slug = "foobar";
        $course->description = "lorem ipsun dolor sit amet";
        $course->image_path = "foobar.jpg";

        $courseDb = $this->courses->create($course->toArray());

        $this->assertNotEmpty($courseDb);
        $this->assertDatabaseHas('courses', $courseDb->toArray());
        $this->assertEquals($course->name, $courseDb->name);
        $this->assertEquals($course->slug, $courseDb->slug);
        $this->assertEquals($course->description, $courseDb->description);
        $this->assertEquals($course->image_path, $courseDb->image_path);
    }

    public function testItShouldBeAbleToUpdateCourse()
    {
        $course = new Course();
        $course->name = "foobar";
        $course->slug = "foobar";
        $course->description = "lorem ipsun dolor sit amet";
        $course->image_path = "foobar.jpg";
        $courseDb = $this->courses->create($course->toArray());

        $attributes = [
            "name" => "Guitar Lessons",
            "slug" => "guitar-lessons",
            "description" => "Guitar and Music Theory lessons",
            "image_path" => "guitar-lessons.jpg",
        ];

        $courseDb2 = $this->courses->update($attributes, $courseDb->id);

        $this->assertNotEmpty($courseDb2);
        $this->assertEquals("Guitar Lessons", $courseDb2->name);
        $this->assertEquals("guitar-lessons", $courseDb2->slug);
        $this->assertEquals("Guitar and Music Theory lessons", $courseDb2->description);
        $this->assertEquals("guitar-lessons.jpg", $courseDb2->image_path);
    }

    public function testItShouldBeAbleToDeleteACourse()
    {
        $course = factory(Course::class)->create();
        $course_id = $course->id;

        $result = $this->courses->delete($course_id);

        $this->assertTrue($result);
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Course] 1");
        $this->courses->show($course_id);
    }
}