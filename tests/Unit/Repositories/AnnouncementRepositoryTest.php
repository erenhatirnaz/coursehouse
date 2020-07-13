<?php

namespace Tests\Unit\Repositories;

use App\ClassRoom;
use Tests\TestCase;
use App\Announcement;
use App\PaymentPeriod;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;
use App\Repositories\AnnouncementRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

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

    public function testItShouldThrowRelationNotFoundExceptionIfAnyGivenRelationIsInvalid()
    {
        factory(Announcement::class)->create();

        $this->expectException(RelationNotFoundException::class);
        $this->expectExceptionMessage("Call to undefined relationship [foo] on model [App\Announcement].");

        $this->announcements->allWithRelations(['foo', 'bar']);
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

    public function testItShouldThrowQueryExceptionIfGivenSlugIsAlreadyExists()
    {
        $announcement = factory(Announcement::class)->make();

        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches("/^(?=.*UNIQUE)(?=.*slug)(?=.*{$announcement->slug}).*$/");

        $this->announcements->create($announcement->toArray());
        $this->announcements->create($announcement->toArray());
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

        $result = $this->announcements->delete($announcement->id);

        $this->assertTrue($result);
        $this->assertDeleted('announcements', $announcement->toArray());
    }

    public function testDeleteMethodShouldThrowNotFoundExceptionIfAnyGivenIdIsNotExists()
    {
        $announcements = factory(Announcement::class, 2)->create();
        $uuid = Str::uuid();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Announcement] {$uuid}");
        $this->announcements->delete([$announcements[0]->id, $uuid, $announcements[1]->id]);
    }

    public function testItShouldCacheAndReturnAllFeaturedAnnouncements()
    {
        factory(Announcement::class, 3)->create(['is_featured' => false]);
        factory(Announcement::class, 4)->create(['is_featured' => true]);

        $featuredAnnouncements = $this->announcements->featured();

        $this->assertNotEmpty($featuredAnnouncements);
        $this->assertCount(4, $featuredAnnouncements);
        $this->assertTrue(
            $featuredAnnouncements[0]->is_featured == 1,
            "`\$featuredAnnouncements[0]->is_featured == 1` must be true, but got false."
        );
        $this->assertTrue(
            Cache::has('announcements.featured'),
            "[Cache(key='announcements.featured')] must not be empty."
        );
    }
}
