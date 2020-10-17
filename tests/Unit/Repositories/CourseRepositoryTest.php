<?php

namespace Tests\Unit\Repositories;

use App\Course;
use App\Teacher;
use Tests\TestCase;
use InvalidArgumentException;
use Illuminate\Database\QueryException;
use App\Repositories\CourseRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

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

    public function testItShouldReturnACourseBySlug()
    {
        factory(Course::class)->create(['slug' => "foo-bar"]);

        $course = $this->courses->getBySlug("foo-bar");

        $this->assertNotEmpty($course);
        $this->assertEquals("foo-bar", $course->slug);
    }

    public function testItShouldHaveCountOfGivenRelations()
    {
        $courseId = factory(Course::class)->create(['slug' => "foo-bar"])->id;
        factory(Teacher::class, 2)->create()->each(function ($teacher) use ($courseId) {
            $teacher->courses()->attach($courseId);
        });

        $course = $this->courses->getBySlug('foo-bar', ['teachers']);

        $this->assertNotEmpty($course);
        $this->assertArrayHasKey('teachers_count', $course->toArray());
        $this->assertEquals(2, $course->teachers_count);
    }

    public function testItShouldThrowNotFoundExceptionIfGivenIdIsNotExists()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Course] 1");

        $this->courses->show(1);
    }

    public function testItShouldThrowNotFouncExceptionIfGivenSlugNotExists()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Course] 'foo-bar-baz'");

        $this->courses->getBySlug("foo-bar-baz");
    }

    public function testItShouldThrowExceptionIfGivenReleationsNotExists()
    {
        factory(Course::class)->create(['slug' => "foo-bar"]);

        $this->expectException(RelationNotFoundException::class);
        $this->expectExceptionMessage("Call to undefined relationship [foo-bar-baz] on model [App\Course].");

        $this->courses->getBySlug("foo-bar", ["foo-bar-baz"]);
    }

    public function testItShouldReturnAllCoursesWithRelatedEntities()
    {
        factory(Course::class, 5)->create();

        $coursesWithRelations = $this->courses->allWithRelations(["teachers"]);

        $this->assertNotEmpty($coursesWithRelations);
        $this->assertArrayHasKey("teachers", $coursesWithRelations[0]->toArray());
    }

    public function testItShouldThrowRelationNotFoundExceptionIfAnyGivenRelationIsInvalid()
    {
        factory(Course::class, 5)->create();

        $this->expectException(RelationNotFoundException::class);
        $this->expectExceptionMessage("Call to undefined relationship [foo] on model [App\Course].");

        $this->courses->allWithRelations(['foo', 'bar']);
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

    public function testItShouldThrowInvalidArgumentExceptionIfGivenArrayIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("All required fields must be given. Empty array isn't allowed!");

        $this->courses->create([]);
    }

    public function testItShouldThrowQueryExceptionIfGivenSlugIsAlreadyExists()
    {
        $course = factory(Course::class)->make();

        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches("/^(?=.*UNIQUE)(?=.*slug)(?=.*{$course->slug}).*$/");
        // TODO: Implement ModelAlreadyExistsException

        $this->courses->create($course->toArray());
        $this->courses->create($course->toArray());
    }

    public function testItShouldBeAbleToUpdateACourse()
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

        $result = $this->courses->delete($course->id);

        $this->assertTrue($result);
        $this->assertDeleted('courses', $course->toArray());
    }

    public function testDeleteMethodShouldThrowModelNotFoundExceptionIfAnyGivenIdIsNotExists()
    {
        $courses = factory(Course::class, 2)->create();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Course] 123");

        $this->courses->delete([$courses[0]->id, 123, $courses[1]->id]);
    }
}
