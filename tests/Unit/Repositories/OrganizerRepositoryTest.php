<?php

namespace Tests\Unit\Repositories;

use App\Course;
use App\Organizer;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use App\Repositories\OrganizerRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

class OrganizerRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $organizers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organizers = $this->app->make(OrganizerRepositoryInterface::class);
    }

    public function testItShouldReturnAllOrganizers()
    {
        factory(Organizer::class, 4)->create();

        $organizers = $this->organizers->all();

        $this->assertNotEmpty($organizers);
        $this->assertCount(4, $organizers);
        $this->assertTrue($organizers[0]->hasRole("organizer"));
    }

    public function testItShouldReturnAllOrganizersWithRelatedEntities()
    {
        factory(Organizer::class, 3)
            ->create()
            ->each(function ($organizer) {
                $organizer->courses()->save(factory(Course::class)->make());
            });

        $organizers = $this->organizers->allWithRelations(['courses']);

        $this->assertNotEmpty($organizers);
        $this->assertCount(3, $organizers);
        $this->assertArrayHasKey("courses", $organizers[0]->toArray());
        $this->assertNotEmpty($organizers[0]->courses);
    }

    public function testItShouldThrowRelationNotFoundExceptionIfAnyGivenRelationIsInvalid()
    {
        factory(Organizer::class, 2)->create();

        $this->expectException(RelationNotFoundException::class);
        $this->expectExceptionMessage("Call to undefined relationship [foobar] on model [App\Organizer].");

        $this->organizers->allWithRelations(['courses', 'foobar', 'roles']);
    }

    public function testItShouldReturnAnOrganizerById()
    {
        $createdOrganizer = factory(Organizer::class)->create(["name" => "John", "surname" => "Doe"]);

        $organizer = $this->organizers->show($createdOrganizer->id);

        $this->assertNotEmpty($organizer);
        $this->assertEquals("John", $organizer->name);
        $this->assertEquals("Doe", $organizer->surname);
    }

    public function testShowMethodShouldThrowModelNotFoundExceptionIfGivenIdIsNotExists()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Organizer] 123");

        $this->organizers->show(123);
    }

    public function testItShouldBeAbleToCreateAnOrganizer()
    {
        $attributes = factory(Organizer::class)->make();
        $attributes->makeVisible("password");

        $organizer = $this->organizers->create($attributes->toArray());

        $this->assertNotEmpty($organizer);
        $this->assertDatabaseHas("users", [
            "name" => $attributes->name,
            "surname" => $attributes->surname,
            "email" => $attributes->email,
            "password" => $attributes->password,
        ]);
    }

    public function testItShouldThrowQueryExceptionIfGivenEmailIsAlreadyExists()
    {
        $attributes = factory(Organizer::class)->make(["phone_no" => null]);
        $attributes->makeVisible("password");

        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches("/^(?=.*UNIQUE)(?=.*email)(?=.*{$attributes->email}).*$/");

        $this->organizers->create($attributes->toArray());
        $this->organizers->create($attributes->toArray());
    }

    public function testItShouldBeAbleToUpdateAnOrganizer()
    {
        $organizerId = factory(Organizer::class)->create()->id;

        $attributes = [
            "name" => "John",
            "surname" => "Doe",
            "email" => "john.doe@example.com",
        ];
        $organizer = $this->organizers->update($attributes, $organizerId);

        $this->assertNotEmpty($organizer);
        $this->assertDatabaseHas("users", [
            "email" => $attributes["email"],
        ]);
        $this->assertTrue($organizer->wasChanged("name"));
        $this->assertTrue($organizer->wasChanged("surname"));
        $this->assertTrue($organizer->wasChanged("email"));
    }

    public function testItShouldBeAbleToDeleteAnOrganizer()
    {
        $organizer = factory(Organizer::class)->create();

        $result = $this->organizers->delete($organizer->id);

        $this->assertTrue($result);
        $this->assertDeleted("users", $organizer->toArray());
    }

    public function testDeleteMethodShouldThrowModelNotFoundExceptionIfAnyGivenIdIsNotExists()
    {
        $organizers = factory(Organizer::class, 2)->create();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Organizer] 123");

        $this->organizers->delete([$organizers[0]->id, 123, $organizers[1]->id]);
    }
}
