<?php

use App\Admin;
use App\Student;
use App\Teacher;
use App\Organizer;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $admin = factory(Admin::class)->create([
            'name' => "Eren",
            'surname' => "Hatırnaz",
            'email' => "eren@example.com",
        ]);
        factory(Student::class, 35)->create();
        factory(Teacher::class, 20)->create();
        factory(Organizer::class, 20)->create();
    }
}
