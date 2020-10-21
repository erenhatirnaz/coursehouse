<?php

namespace Tests\Unit\Repositories;

use App\Course;
use App\ClassRoom;
use Tests\TestCase;
use InvalidArgumentException;
use Illuminate\Database\QueryException;
use App\Repositories\ClassRoomRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

class ClassRoomRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var ClassRoomRepository
     */
    private $classRoomRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classRoomRepository = $this->app->make(ClassRoomRepositoryInterface::class);
    }

    public function testItShouldReturnAllClassRooms()
    {
        $courseId = factory(Course::class)->create()->id;
        $classRooms = factory(ClassRoom::class, 2)->create([ 'course_id' => $courseId ]);

        $classRooms = $this->classRoomRepository->all();

        $this->assertNotEmpty($classRooms);
        $this->assertCount(2, $classRooms);
    }

    public function testItShouldReturnAllClassRoomsWithRelatedEntities()
    {
        factory(ClassRoom::class, 4)->create();

        $allWithRelations = $this->classRoomRepository->allWithRelations(['course']);

        $this->assertNotEmpty($allWithRelations);
        $this->assertCount(4, $allWithRelations);
        $this->assertArrayHasKey("course", $allWithRelations[0]->toArray());
        $this->assertNotNull($allWithRelations[0]->course);
        $this->assertInstanceOf(Course::class, $allWithRelations[0]->course);
    }

    public function testItShouldThrowRelationNotFoundExceptionIfAnyGivenRelationIsInvalid()
    {
        factory(ClassRoom::class, 2)->create();

        $this->expectException(RelationNotFoundException::class);
        $this->expectExceptionMessage("Call to undefined relationship [foobar] on model [App\ClassRoom].");

        $this->classRoomRepository->allWithRelations(['foobar']);
    }

    public function testItShouldReturnAnClassRoomById()
    {
        $classRoomId = factory(ClassRoom::class)->create(['name' => "Beginner 1/A", 'description' => "foo bar"])->id;

        $classRoom = $this->classRoomRepository->show($classRoomId);

        $this->assertNotEmpty($classRoom);
        $this->assertEquals("Beginner 1/A", $classRoom->name);
        $this->assertEquals("foo bar", $classRoom->description);
    }

    public function testShowMethodShouldThrowModelNotFoundExceptionIfGivenIdIsNotExists()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\ClassRoom] 123");

        $this->classRoomRepository->show(123);
    }

    public function testItShouldBeAbleToCreateAClassRoom()
    {
        $courseId = factory(Course::class)->create()->id;
        $attributes = factory(ClassRoom::class)->make(['course_id' => $courseId]);

        $classRoom = $this->classRoomRepository->create($attributes->toArray());

        $this->assertNotEmpty($classRoom);
        $this->assertDatabaseHas("class_rooms", [
            "name" => $attributes->name,
            "description" => $attributes->description,
            "age_range_min" => $attributes->age_range_min,
            "age_range_max" => $attributes->age_range_max,
        ]);
    }

    public function testItShouldThrowInvalidArgumentExceptionIfGivenArrayIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("All required fields must be given. Empty array isn't allowed!");

        $this->classRoomRepository->create([]);
    }

    public function testItShouldThrowQueryExceptionIfGivenSlugIsAlreadyExists()
    {
        $classRoom = factory(ClassRoom::class)->make();

        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches("/^(?=.*UNIQUE)(?=.*slug)(?=.*{$classRoom->slug}).*$/");

        $this->classRoomRepository->create($classRoom->toArray());
        $this->classRoomRepository->create($classRoom->toArray());
    }

    public function testItShouldBeAbleToUpdateClassRoom()
    {
        $classRoom = factory(ClassRoom::class)->create();

        $attributes = [
            "name" => "Expert 2/B",
            "slug" => "expert-2b-a2b4c85",
        ];
        $classRoomDb = $this->classRoomRepository->update($attributes, $classRoom->id);

        $this->assertNotEmpty($classRoomDb);
        $this->assertDatabaseHas("class_rooms", $attributes);
        $this->assertTrue($classRoomDb->wasChanged("name"));
        $this->assertTrue($classRoomDb->wasChanged("slug"));
    }

    public function testItShouldBeAbleToDeleteAClassRoom()
    {
        $classRoom = factory(ClassRoom::class)->create();

        $result = $this->classRoomRepository->delete($classRoom->id);

        $this->assertTrue($result);
        $this->assertDeleted('class_rooms', $classRoom->toArray());
    }

    public function testDeleteMethodShouldThrowModelNotFoundExceptionIfAnyGivenIdIsNotExists()
    {
        $classRooms = factory(ClassRoom::class, 2)->create();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\ClassRoom] 123");
        $this->classRoomRepository->delete([$classRooms[0]->id, 123, $classRooms[1]->id]);
    }
}
