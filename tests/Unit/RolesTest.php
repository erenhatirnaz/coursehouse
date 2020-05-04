<?php

namespace Tests\Unit;

use App\Role;
use App\Roles;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RolesTest extends TestCase
{
    use DatabaseMigrations;

    public function testValuesShouldMatchRoleIdsOnDb()
    {
        $admin_id = Role::firstWhere('name', 'admin')->id;
        $student_id = Role::firstWhere('name', 'student')->id;
        $teacher_id = Role::firstWhere('name', 'teacher')->id;
        $organizer_id = Role::firstWhere('name', 'organizer')->id;

        $this->assertEquals(Roles::ADMIN, $admin_id);
        $this->assertEquals(Roles::STUDENT, $student_id);
        $this->assertEquals(Roles::TEACHER, $teacher_id);
        $this->assertEquals(Roles::ORGANIZER, $organizer_id);
    }
}
