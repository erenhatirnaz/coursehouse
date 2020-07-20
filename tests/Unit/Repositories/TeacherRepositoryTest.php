<?php

namespace Tests\Unit\Repositories;

use App\Course;
use App\Teacher;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use App\Repositories\TeacherRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

class TeacherRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $teachers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teachers = $this->app->make(TeacherRepositoryInterface::class);
    }

    public function testItShouldReturnAllTeachers()
    {
        factory(Teacher::class, 6)->create();

        $teachers = $this->teachers->all();

        $this->assertNotEmpty($teachers);
        $this->assertCount(6, $teachers);
        $this->assertTrue($teachers[0]->hasRole("teacher"));
        $this->assertInstanceOf(Teacher::class, $teachers[0]);
    }

    public function testItShouldReturnAllTeachersWithRelatedEntities()
    {
        factory(Teacher::class, 8)
            ->create()
            ->each(function ($teacher) {
                $teacher->courses()->save(factory(Course::class)->make());
            });

        $teachers = $this->teachers->allWithRelations(['courses']);

        $this->assertNotEmpty($teachers);
        $this->assertCount(8, $teachers);
        $this->assertArrayHasKey("courses", $teachers[0]->toArray());
        $this->assertNotEmpty($teachers[0]->courses);
    }

    public function testItShouldThrowRelationNotFoundExceptionIfAnyGivenRelationIsInvalid()
    {
        factory(Teacher::class, 2)->create();

        $this->expectException(RelationNotFoundException::class);
        $this->expectExceptionMessage("Call to undefined relationship [foobar] on model [App\Teacher].");

        $this->teachers->allWithRelations(['courses', 'foobar', 'roles']);
    }

    public function testItShouldBeReturnATeacherById()
    {
        $teacherId = factory(Teacher::class)->create(["name" => "John", "surname" => "Smith"])->id;

        $teacher = $this->teachers->show($teacherId);

        $this->assertNotEmpty($teacher);
        $this->assertEquals("John", $teacher->name);
        $this->assertEquals("Smith", $teacher->surname);
    }

    public function testShowMethodShouldThrowModelNotFoundExceptionIfGivenIdIsNotExists()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Teacher] 123");

        $this->teachers->show(123);
    }

    public function testItShouldBeCreateATeacher()
    {
        $attributes = factory(Teacher::class)->make();
        $attributes->makeVisible("password");

        $teacher = $this->teachers->create($attributes->toArray());

        $this->assertNotEmpty($teacher);
        $this->assertDatabaseHas("users", [
            "name" => $attributes->name,
            "surname" => $attributes->surname,
            "email" => $attributes->email,
            "password" => $attributes->password,
        ]);
    }

    public function testItShouldThrowQueryExceptionIfGivenEmailIsAlreadyExists()
    {
        $attributes = factory(Teacher::class)->make(["phone_no" => null]);
        $attributes->makeVisible("password");

        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches("/^(?=.*UNIQUE)(?=.*email)(?=.*{$attributes->email}).*$/");

        $this->teachers->create($attributes->toArray());
        $this->teachers->create($attributes->toArray());
    }

    public function testItShouldBeAbleToUpdateATeacher()
    {
        $teacherId = factory(Teacher::class)->create()->id;

        $attributes = [
            "name" => "John",
            "surname" => "Smith",
            "email" => "john.smith@example.org",
        ];
        $teacher = $this->teachers->update($attributes, $teacherId);

        $this->assertNotEmpty($teacher);
        $this->assertDatabaseHas("users", [
            "email" => $attributes["email"],
        ]);
        $this->assertTrue($teacher->wasChanged("name"));
        $this->assertTrue($teacher->wasChanged("surname"));
        $this->assertTrue($teacher->wasChanged("email"));
    }

    public function testItShouldBeAbleToDeleteATeacher()
    {
        $teacher = factory(Teacher::class)->create();

        $result = $this->teachers->delete($teacher->id);

        $this->assertTrue($result);
        $this->assertDeleted("users", $teacher->toArray());
    }

    public function testDeleteMethodShouldThrowModelNotFoundExceptionIfAnyGivenIdIsNotExists()
    {
        $teachers = factory(Teacher::class, 2)->create();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Teacher] 123");

        $this->teachers->delete([$teachers[0]->id, 123, $teachers[1]->id]);
    }
}
