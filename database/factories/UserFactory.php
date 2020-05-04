<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Admin;
use App\Student;
use App\Teacher;
use App\Organizer;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function(Faker $faker) {
    return [
        'name' => $faker->name,
        'surname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'phone_no' => $faker->tollFreePhoneNumber,
        'birth_date' => $faker->dateTimeBetween('-40 years', 'now'),
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Admin::class, function (Faker $faker) {
    return factory(User::class)->raw([]);
});

$factory->define(Student::class, function (Faker $faker) {
    return factory(User::class)->raw([]);
});

$factory->define(Teacher::class, function (Faker $faker) {
    return factory(User::class)->raw([]);
});

$factory->define(Organizer::class, function (Faker $faker) {
    return factory(User::class)->raw([]);
});
