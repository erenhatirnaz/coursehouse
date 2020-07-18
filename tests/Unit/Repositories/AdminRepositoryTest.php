<?php

namespace Tests\Unit\Repositories;

use App\Admin;
use Tests\TestCase;
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
    }

    public function testItShouldReturnAllAdminsWithRelatedEntities()
    {
        factory(Admin::class, 3)->create();

        $allWithRelations = $this->admins->allWithRelations(['roles']);

        $this->assertNotEmpty($allWithRelations);
        $this->assertCount(3, $allWithRelations);
        $this->assertArrayHasKey("roles", $allWithRelations[0]->toArray());
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
        $createdAdmin = factory(Admin::class)->create(["name" => "John", "surname" => "Doe"]);

        $admin = $this->admins->show($createdAdmin->id);

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
        $admin = factory(Admin::class)->create();

        $attributes = [
            "name" => "John",
            "surname" => "Doe",
            "email" => "john.doe@example.net",
        ];
        $adminDb = $this->admins->update($attributes, $admin->id);

        $this->assertNotEmpty($adminDb);
        $this->assertDatabaseHas("users", [
            "email" => $adminDb->email
        ]);
        $this->assertTrue($adminDb->wasChanged("name"));
        $this->assertTrue($adminDb->wasChanged("surname"));
        $this->assertTrue($adminDb->wasChanged("email"));
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
