<?php

namespace Tests\Unit;

use App\Announcement;
use App\Student;
use App\Application;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ApplicationTest extends TestCase
{
    use DatabaseMigrations;

    public function testItShouldBelongsToStudentAndAnnouncement()
    {
        $announcement = factory(Announcement::class)->create();
        $student = factory(Student::class)->create();

        $application = factory(Application::class)->create([
            'announcement_id' => $announcement->id,
            'student_id' => $student->id,
        ]);

        $this->assertTrue($application->announcement()->exists());
        $this->assertTrue($application->student()->exists());
        $this->assertEquals($announcement->id, $application->announcement->id);
        $this->assertEquals($student->id, $application->student->id);
    }
}
