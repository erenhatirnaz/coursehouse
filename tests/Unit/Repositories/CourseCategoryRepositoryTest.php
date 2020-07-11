<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\CourseCategory;
use App\Repositories\CourseCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CourseCategoryRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $courseCategories;

    public function setUp(): void
    {
        parent::setUp();

        CourseCategory::destroy(CourseCategory::all()->modelKeys()); // destroy default categories
        $this->courseCategories = $this->app->make(CourseCategoryRepositoryInterface::class);
    }

    public function testItShouldReturnAllCourseCategories()
    {
        factory(CourseCategory::class, 6)->create();

        $courseCategories = $this->courseCategories->all();

        $this->assertNotEmpty($courseCategories);
        $this->assertCount(6, $courseCategories);
    }

    public function testItShouldReturnACourseCategoryById()
    {
        $createdCategory = factory(CourseCategory::class)->create([
            "name" => "Foobar",
            "slug" => "foobar",
        ]);

        $courseCategory = $this->courseCategories->show($createdCategory->id);

        $this->assertNotEmpty($courseCategory);
        $this->assertEquals("Foobar", $courseCategory->name);
        $this->assertEquals("foobar", $courseCategory->slug);
    }

    public function testShowMethodShouldThrowNotFoundExceptionIfGivenIdIsNotExists()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\CourseCategory] 1");

        $this->courseCategories->show(1);
    }

    public function testItShouldBeAbleToCreateACourseCategory()
    {
        $attributes = [
            "slug" => "foobar-baz",
            "name" => "Foobar Baz",
        ];

        $courseCategory = $this->courseCategories->create($attributes);

        $this->assertNotEmpty($courseCategory);
        $this->assertDatabaseHas("course_categories", [
            "slug" => "foobar-baz",
            "name" => "Foobar Baz",
        ]);
    }

    public function testItShouldBeAbleToUpdateACourseCategory()
    {
        $courseCategory = factory(CourseCategory::class)->create();

        $attributes = [
            "slug" => "computer-science",
            "name" => "Computer Science",
        ];
        $courseCategoryDb = $this->courseCategories->update($attributes, $courseCategory->id);

        $this->assertNotEmpty($courseCategoryDb);
        $this->assertDatabaseHas('course_categories', [
            "slug" => "computer-science",
            "name" => "Computer Science",
        ]);
        $this->assertTrue($courseCategoryDb->wasChanged("slug"));
        $this->assertTrue($courseCategoryDb->wasChanged("name"));
    }

    public function testItShouldBeAbleToDeleteACourseCategory()
    {
        $courseCategory = factory(CourseCategory::class)->create();

        $result = $this->courseCategories->delete($courseCategory->id);

        $this->assertTrue($result);
        $this->assertDeleted('course_categories', $courseCategory->toArray());
    }

    public function testDeleteMethodShouldThrowNotFoundExceptionIfAnyGivenIdIsNotExists()
    {
        $courseCategories = factory(CourseCategory::class, 2)->create();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\CourseCategory] 123");

        $this->courseCategories->delete([$courseCategories[0]->id, 123, $courseCategories[1]->id]);
    }
}
