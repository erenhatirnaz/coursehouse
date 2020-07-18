<?php

namespace Tests\Unit\Repositories;

use App\Student;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use App\Repositories\StudentRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

class StudentRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $students;

    public function setUp(): void
    {
        parent::setUp();

        $this->students = $this->app->make(StudentRepositoryInterface::class);
    }

    public function testItShouldReturnAllStudents()
    {
        factory(Student::class, 5)->create();

        $students = $this->students->all();

        $this->assertNotEmpty($students);
        $this->assertCount(5, $students);
        $this->assertTrue($students[0]->hasRole('student'));
    }

    public function testItShouldReturnAllStudentsWithRelatedEntities()
    {
        factory(Student::class, 3)->create();

        $allWithRelations = $this->students->allWithRelations(['applications']);

        $this->assertNotEmpty($allWithRelations);
        $this->assertCount(3, $allWithRelations);
        $this->assertArrayHasKey("applications", $allWithRelations[0]->toArray());
    }

    public function testItShouldThrowRelationNotFoundExceptionIfAnyGivenRelationIsInvalid()
    {
        factory(Student::class, 2)->create();

        $this->expectException(RelationNotFoundException::class);
        $this->expectExceptionMessage("Call to undefined relationship [foobar] on model [App\Student].");

        $this->students->allWithRelations(['applications', 'foobar']);
    }

    public function testItShouldReturnAnStudentById()
    {
        $createdStudent = factory(Student::class)->create(["name" => "John", "surname" => "Doe"]);

        $student = $this->students->show($createdStudent->id);

        $this->assertNotEmpty($student);
        $this->assertEquals("John", $student->name);
        $this->assertEquals("Doe", $student->surname);
    }

    public function testShowMethodShouldThrowModelNotFoundExceptionIfGivenIdIsNotExists()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Student] 123");

        $this->students->show(123);
    }

    public function testItShouldBeAbleToCreateAStudent()
    {
        $attributes = factory(Student::class)->make();
        $attributes->makeVisible("password");

        $student = $this->students->create($attributes->toArray());

        $this->assertNotEmpty($student);
        $this->assertDatabaseHas("users", [
            "name" => $attributes->name,
            "surname" => $attributes->surname,
            "email" => $attributes->email,
            "password" => $attributes->password,
        ]);
    }

    public function testItShouldThrowQueryExceptionIfGivenEmailIsAlreadyExists()
    {
        $attributes = factory(Student::class)->make(["phone_no" => null]);
        $attributes->makeVisible("password");

        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches("/^(?=.*UNIQUE)(?=.*email)(?=.*{$attributes->email}).*$/");

        $this->students->create($attributes->toArray());
        $this->students->create($attributes->toArray());
    }

    public function testItShouldBeAbleToUpdateAStudent()
    {
        $studentId = factory(Student::class)->create()->id;

        $attributes = [
            "name" => "John",
            "surname" => "Doe",
            "email" => "john.doe@example.net",
        ];
        $student = $this->students->update($attributes, $studentId);

        $this->assertNotEmpty($student);
        $this->assertDatabaseHas("users", [
            "email" => $attributes["email"],
        ]);
        $this->assertTrue($student->wasChanged("name"));
        $this->assertTrue($student->wasChanged("surname"));
        $this->assertTrue($student->wasChanged("email"));
    }

    public function testItShouldBeAbleToDeleteAnStudent()
    {
        $student = factory(Student::class)->create();

        $result = $this->students->delete($student->id);

        $this->assertTrue($result);
        $this->assertDeleted('users', $student->toArray());
    }

    public function testDeleteMethodShouldThrowModelNotFoundExceptionIfAnyGivenIdIsNotExists()
    {
        $students = factory(Student::class, 2)->create();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Student] 123");

        $this->students->delete([$students[0]->id, 123, $students[1]->id]);
    }
}
