<?php

namespace Tests\Unit;

use App\Teacher;
use App\User;
use App\Roles;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeacherTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreatedEventShouldBeTriggeredWhenTeacherInsertedToDb()
    {
        $teacher = factory(Teacher::class, 1)->create()->first();

        $this->assertNotEmpty($teacher);
        $this->assertEquals(Roles::TEACHER, $teacher->roles->first()->id);
    }

    public function testGlobalScopeShouldBeApplied()
    {
        factory(Teacher::class, 3)->create();
        factory(User::class, 4)->create();

        $this->assertCount(3, Teacher::all()->toArray());
    }
}
