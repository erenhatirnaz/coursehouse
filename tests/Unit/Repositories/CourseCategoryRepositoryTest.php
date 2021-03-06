<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\CourseCategory;
use InvalidArgumentException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\CourseCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

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

    public function testItShouldReturnAllCourseCategoriesWithRelatedEntities()
    {
        factory(CourseCategory::class, 1)->create();

        $courseCategoriesWithRelations = $this->courseCategories->allWithRelations(['courses']);

        $this->assertNotEmpty($courseCategoriesWithRelations);
        $this->assertArrayHasKey("courses", $courseCategoriesWithRelations[0]);
    }

    public function testItShouldThrowRelationNotFoundExceptionIfAnyGivenRelationIsInvalid()
    {
        factory(CourseCategory::class, 1)->create();

        $this->expectException(RelationNotFoundException::class);
        $this->expectExceptionMessage("Call to undefined relationship [foo] on model [App\CourseCategory].");

        $this->courseCategories->allWithRelations(['foo', 'bar']);
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

    public function testItShouldThrowInvalidArgumentExceptionIfGivenArrayIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("All required fields must be given. Empty array isn't allowed!");

        $this->courseCategories->create([]);
    }

    public function testItShouldThrowQueryExceptionIfGivenSlugIsAlreadyExists()
    {
        $courseCategory = factory(CourseCategory::class)->make();

        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches("/^(?=.*UNIQUE)(?=.*slug)(?=.*{$courseCategory->slug}).*$/");

        $this->courseCategories->create($courseCategory->toArray());
        $this->courseCategories->create($courseCategory->toArray());
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
