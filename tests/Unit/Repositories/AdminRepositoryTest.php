<?php

namespace Tests\Unit\Repositories;

use App\Admin;
use Tests\TestCase;
use InvalidArgumentException;
use Illuminate\Database\QueryException;
use App\Repositories\AdminRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

class AdminRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $admins;

    public function setUp(): void
    {
        parent::setUp();

        $this->admins = $this->app->make(AdminRepositoryInterface::class);
    }

    public function testItShouldReturnAllAdmins()
    {
        factory(Admin::class, 2)->create();

        $admins = $this->admins->all();

        $this->assertNotEmpty($admins);
        $this->assertCount(2, $admins);
        $this->assertTrue($admins[0]->hasRole('admin'));
        $this->assertInstanceOf(Admin::class, $admins[0]);
    }

    public function testItShouldReturnAllAdminsWithRelatedEntities()
    {
        factory(Admin::class, 3)->create();

        $allWithRelations = $this->admins->allWithRelations(['roles']);

        $this->assertNotEmpty($allWithRelations);
        $this->assertCount(3, $allWithRelations);
        $this->assertArrayHasKey("roles", $allWithRelations[0]->toArray());
        $this->assertNotEmpty($allWithRelations[0]->roles);
    }

    public function testItShouldThrowRelationNotFoundExceptionIfAnyGivenRelationIsInvalid()
    {
        factory(Admin::class, 2)->create();

        $this->expectException(RelationNotFoundException::class);
        $this->expectExceptionMessage("Call to undefined relationship [foobar] on model [App\Admin].");

        $this->admins->allWithRelations(['roles', 'foobar']);
    }

    public function testItShouldReturnAnAdminById()
    {
        $adminId = factory(Admin::class)->create(["name" => "John", "surname" => "Doe"])->id;

        $admin = $this->admins->show($adminId);

        $this->assertNotEmpty($admin);
        $this->assertEquals("John", $admin->name);
        $this->assertEquals("Doe", $admin->surname);
    }

    public function testShowMethodShouldThrowModelNotFoundExceptionIfGivenIdIsNotExists()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Admin] 123");

        $this->admins->show(123);
    }

    public function testItShouldBeAbleToCreateAnAdmin()
    {
        $attributes = factory(Admin::class)->make();
        $attributes->makeVisible("password");

        $admin = $this->admins->create($attributes->toArray());

        $this->assertNotEmpty($admin);
        $this->assertDatabaseHas("users", [
            "name" => $attributes->name,
            "surname" => $attributes->surname,
            "email" => $attributes->email,
            "password" => $attributes->password,
        ]);
    }

    public function testItShouldThrowInvalidArgumentExceptionIfGivenArrayIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("All required fields must be given. Empty array isn't allowed!");

        $this->admins->create([]);
    }

    public function testItShouldThrowQueryExceptionIfGivenEmailIsAlreadyExists()
    {
        $attributes = factory(Admin::class)->make(["phone_no" => null]);
        $attributes->makeVisible("password");

        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches("/^(?=.*UNIQUE)(?=.*email)(?=.*{$attributes->email}).*$/");

        $this->admins->create($attributes->toArray());
        $this->admins->create($attributes->toArray());
    }

    public function testItShouldBeAbleToUpdateAnAdmin()
    {
        $adminId = factory(Admin::class)->create()->id;

        $attributes = [
            "name" => "John",
            "surname" => "Doe",
            "email" => "john.doe@example.net",
        ];
        $admin = $this->admins->update($attributes, $adminId);

        $this->assertNotEmpty($admin);
        $this->assertDatabaseHas("users", [
            "email" => $attributes["email"],
        ]);
        $this->assertTrue($admin->wasChanged("name"));
        $this->assertTrue($admin->wasChanged("surname"));
        $this->assertTrue($admin->wasChanged("email"));
    }

    public function testItShouldBeAbleToDeleteAnAdmin()
    {
        $admin = factory(Admin::class)->create();

        $result = $this->admins->delete($admin->id);

        $this->assertTrue($result);
        $this->assertDeleted('users', $admin->toArray());
    }

    public function testDeleteMethodShouldThrowModelNotFoundExceptionIfAnyGivenIdIsNotExists()
    {
        $admins = factory(Admin::class, 2)->create();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Admin] 123");

        $this->admins->delete([$admins[0]->id, 123, $admins[1]->id]);
    }
}
