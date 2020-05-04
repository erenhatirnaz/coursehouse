<?php

namespace Tests\Unit;

use App\Student;
use App\User;
use App\Roles;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StudentTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreatedEventShouldBeTriggeredWhenStudentInsertedToDb()
    {
        $student = factory(Student::class, 1)->create()->first();

        $this->assertNotEmpty($student);
        $this->assertEquals(Roles::STUDENT, $student->roles->first()->id);
    }

    public function testGlobalScopeShouldBeApplied()
    {
        factory(Student::class, 3)->create();
        factory(User::class, 4)->create();

        $this->assertCount(3, Student::all()->toArray());
    }
}
