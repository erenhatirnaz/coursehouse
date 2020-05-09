<?php

namespace Tests\Unit;

use App\User;
use App\Roles;
use App\Student;
use App\ClassRoom;
use Tests\TestCase;
use App\Application;
use App\Announcement;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StudentTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreatedEventShouldBeTriggeredWhenStudentInsertedToDb()
    {
        $student = factory(Student::class)->create();

        $this->assertNotEmpty($student);
        $this->assertEquals(Roles::STUDENT, $student->roles->first()->id);
    }

    public function testGlobalScopeShouldBeApplied()
    {
        factory(Student::class, 3)->create();
        factory(User::class, 4)->create();

        $this->assertCount(3, Student::all()->toArray());
    }

    public function testItShouldHasManyClassRooms()
    {
        $student = factory(Student::class)->create();

        $classRoom1 = factory(ClassRoom::class)->create();
        $student->classRooms()->attach($classRoom1->id);

        $classRoom2 = factory(ClassRoom::class)->create();
        $student->classRooms()->attach($classRoom2->id);

        $this->assertTrue($student->classRooms()->exists());
        $this->assertEquals($classRoom1->id, $student->classRooms[0]->id);
        $this->assertEquals($classRoom2->id, $student->classRooms[1]->id);
    }

    public function testItShouldBelongsToApplications()
    {
        $student = factory(Student::class)->create();

        $application1 = factory(Application::class)->create([
            'student_id' => $student->id,
        ]);
        $application2 = factory(Application::class)->create([
            'student_id' => $student->id,
        ]);
        $application3 = factory(Application::class)->create([
            'student_id' => $student->id,
        ]);

        $this->assertTrue($student->applications()->exists());
        $this->assertInstanceOf(Application::class, $student->applications()->find($application1->id));
        $this->assertInstanceOf(Application::class, $student->applications()->find($application2->id));
        $this->assertInstanceOf(Application::class, $student->applications()->find($application3->id));
    }
}
