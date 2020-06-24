<?php

namespace Tests\Unit;

use App\Student;
use App\ClassRoom;
use Tests\TestCase;
use App\Announcement;
use App\Application;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AnnouncementTest extends TestCase
{
    use DatabaseMigrations;

    public function testItBelongsToClassRoom()
    {
        $classRoom = factory(ClassRoom::class)->create();
        $announcement = factory(Announcement::class)->create([
            'class_room_id' => $classRoom->id,
        ]);

        $this->assertTrue($announcement->classRoom()->exists());
        $this->assertEquals($classRoom->id, $announcement->classRoom->id);
    }

    public function testItShoulHasManyApplications()
    {
        $classRoom = factory(ClassRoom::class)->create();
        $announcement = factory(Announcement::class)->create([
            'class_room_id' => $classRoom->id,
        ]);

        $student1 = factory(Student::class)->create();
        $application1 = factory(Application::class)->create([
            'student_id' => $student1->id,
            'announcement_id' => $announcement->id,
        ]);

        $student2 = factory(Student::class)->create();
        $application2 = factory(Application::class)->create([
            'student_id' => $student2->id,
            'announcement_id' => $announcement->id,
        ]);

        $this->assertTrue($announcement->applications()->exists());
        $this->assertEquals($application1->id, $announcement->applications[0]->id);
        $this->assertEquals($application2->id, $announcement->applications[1]->id);
    }

    public function testLinkAttributeShouldReturnRoute()
    {
        $announcement = factory(Announcement::class)->create([
            'title' => 'foobar',
            'slug' => 'foobar'
        ]);

        $this->assertStringContainsString("/announcement/foobar", $announcement->link);
    }

    public function testPosterImageAttributeShouldReturnLocalPathIfNotStartsWithHttp()
    {
        $announcement = factory(Announcement::class)->create([
            "poster_image_path" => "foobar.png"
        ]);

        $this->assertStringContainsString("/img/announcements/foobar.png", $announcement->poster_image);
    }

    public function testPosterImageAttributeShouldReturnRemoteUrlIfStartsWithHttp()
    {
        $announcement = factory(Announcement::class)->create();

        $this->assertStringStartsWith("https://", $announcement->poster_image);
    }
}
