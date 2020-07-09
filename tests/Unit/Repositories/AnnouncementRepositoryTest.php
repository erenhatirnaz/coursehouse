<?php

namespace Tests\Unit\Repositories;

use App\ClassRoom;
use Tests\TestCase;
use App\Announcement;
use App\PaymentPeriod;
use Illuminate\Support\Str;
use App\Repositories\AnnouncementRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AnnouncementRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $announcements;

    protected function setUp(): void
    {
        parent::setUp();

        $this->announcements = $this->app->make(AnnouncementRepositoryInterface::class);
    }

    public function testItShouldReturnAllAnnouncements()
    {
        factory(Announcement::class, 5)->create();

        $announcements = $this->announcements->all();

        $this->assertNotEmpty($announcements);
        $this->assertCount(5, $announcements);
        $this->assertArrayNotHasKey("class_room", $announcements[0]->toArray());
    }

    public function testItShouldReturnAnAnnouncementById()
    {
        $createdAnnouncement = factory(Announcement::class)->create(["title" => "foo"]);

        $announcement = $this->announcements->show($createdAnnouncement->id);

        $this->assertNotEmpty($announcement);
        $this->assertEquals("foo", $announcement->title);
    }

    public function testShowMethodShouldThrowNotFoundExceptionIfGivenIdIsNotExists()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Announcement] 1");

        $this->announcements->show(1);
    }

    public function testItShouldReturnAllAnnouncementsWithRelatedEntities()
    {
        factory(Announcement::class, 5)->create();

        $announcementsWithRelations = $this->announcements->allWithRelations(["classRoom"]);

        $this->assertNotEmpty($announcementsWithRelations);
        $this->assertArrayHasKey("class_room", $announcementsWithRelations[0]->toArray());
    }

    public function testItShouldBeAbleToCreateAnAnnouncement()
    {
        $classRoom = factory(ClassRoom::class)->create();

        $announcement = [
            "id" => Str::uuid(),
            "title" => "Foobar",
            "slug" => "foobar-123",
            "description" => "lorem ipsun dolor sit amet",
            "poster_image_path" => "foobar-123.png",
            "starts_at" => now(),
            "ends_at" => now()->addWeek(),
            "quota" => 5,
            "price" => 100,
            "payment_period" => PaymentPeriod::MONTHLY,
            "is_featured" => false,
            "class_room_id" => $classRoom->id,
        ];

        $announcementDb = $this->announcements->create($announcement);

        $this->assertNotEmpty($announcementDb);
        $this->assertDatabaseHas('announcements', [
            "id" => $announcement["id"],
            "title" => $announcement["title"],
            "slug" => $announcement["slug"],
        ]);
    }

    public function testItShouldBeAbleToUpdateAnnouncement()
    {
        $classRoom = factory(ClassRoom::class)->create();

        $announcement = [
            "id" => Str::uuid(),
            "title" => "Foobar",
            "slug" => "foobar-123",
            "description" => "lorem ipsun dolor sit amet",
            "poster_image_path" => "foobar-123.png",
            "starts_at" => now(),
            "ends_at" => now()->addWeek(),
            "quota" => 5,
            "price" => 100,
            "payment_period" => PaymentPeriod::MONTHLY,
            "is_featured" => false,
            "class_room_id" => $classRoom->id,
        ];
        $this->announcements->create($announcement);

        $attributes = [
            "title" => "Guitar Lessons started!",
            "slug" => "guitar-lessons-started-123",
        ];
        $announcementDb = $this->announcements->update($attributes, $announcement["id"]);

        $this->assertNotEmpty($announcementDb);
        $this->assertDatabaseHas('announcements', [
            "title" => "Guitar Lessons started!",
            "slug" => "guitar-lessons-started-123",
        ]);
        $this->assertTrue($announcementDb->wasChanged("title"));
        $this->assertTrue($announcementDb->wasChanged("slug"));
    }

    public function testItShouldBeAbleToDeleteAnAnnouncement()
    {
        $announcement = factory(Announcement::class)->create();
        $announcement_id = $announcement->id;

        $result = $this->announcements->delete($announcement_id);

        $this->assertTrue($result);
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Announcement] {$announcement_id}");
        $this->announcements->show($announcement_id);
    }

    public function testDeleteMethodShouldThrowNotFoundExceptionIfAnyGivenIdIsNotExists()
    {
        $announcements = factory(Announcement::class, 2)->create();
        $uuid = Str::uuid();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Announcement] {$uuid}");
        $this->announcements->delete([$announcements[0]->id, $uuid, $announcements[1]->id]);
    }
}
