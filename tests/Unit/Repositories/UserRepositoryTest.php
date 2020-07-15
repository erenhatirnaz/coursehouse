<?php

namespace Tests\Unit\Repositories;

use App\User;
use App\Student;
use Tests\TestCase;
use BadMethodCallException;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $users;

    public function setUp(): void
    {
        parent::setUp();

        $this->users = $this->app->make(UserRepositoryInterface::class);
    }

    public function testItShouldReturnAllUsers()
    {
        factory(User::class, 4)->create();

        $users = $this->users->all();

        $this->assertNotEmpty($users);
        $this->assertCount(4, $users);
    }

    public function testItShouldReturnAllUsersWithRelatedEntities()
    {
        // TODO: The User model must have default roles.
        factory(Student::class, 5)->create();

        $allWithRelations = $this->users->allWithRelations(['roles']);

        $this->assertNotEmpty($allWithRelations);
        $this->assertCount(5, $allWithRelations);
        $this->assertArrayHasKey('roles', $allWithRelations[0]->toArray());
        $this->assertNotEmpty($allWithRelations[0]->roles->toArray());
    }

    public function testItShouldThrowRelationNotFoundExceptionIfAnyGivenRelationIsInvalid()
    {
        factory(User::class, 2)->create();

        $this->expectException(RelationNotFoundException::class);
        $this->expectExceptionMessage("Call to undefined relationship [courses] on model [App\User].");

        $this->users->allWithRelations(['roles', 'courses']);
    }

    public function testItShouldReturnAnUserById()
    {
        $createdUser = factory(User::class)->create(["name" => "John", "surname" => "Doe"]);

        $user = $this->users->show($createdUser->id);

        $this->assertNotEmpty($user);
        $this->assertEquals("John", $user->name);
        $this->assertEquals("Doe", $user->surname);
    }

    public function testItShouldThrowModelNotFoundExceptionIfGivenIdIsNotExists()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\User] 123");

        $this->users->show(123);
    }

    public function testItShouldBeAbleToDeleteAnUser()
    {
        $user = factory(User::class)->create();

        $result = $this->users->delete($user->id);

        $this->assertTrue($result);
        $this->assertDeleted('users', $user->toArray());
    }

    public function testDeleteMethodShouldThrowModelNotFoundExceptionIfAnyGivenIdIsNotExists()
    {
        $users = factory(User::class, 2)->create();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\User] 12");

        $this->users->delete([$users[0]->id, 12, $users[1]->id]);
    }

    public function testCreateMethodShouldThrowBadMethodCallException()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            "`create` method is disabled for this repository. Instead, use one of them: " .
            "AdminRepository, OrganizerRepository, TeacherRepository, StudentRepository."
        );

        $this->users->create([]);
    }

    public function testUpdateMethodShouldThrowBadMethodCallException()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            "`update` method is disabled for this repository. Instead, use one of them: " .
            "AdminRepository, OrganizerRepository, TeacherRepository, StudentRepository."
        );

        $this->users->update([], 123);
    }
}
