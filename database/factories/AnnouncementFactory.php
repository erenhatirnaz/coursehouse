<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
use App\ClassRoom;
use App\Application;
use App\Announcement;
use App\PaymentPeriod;
use App\ApplicationStatus;
use Faker\Generator as Faker;

$factory->define(Announcement::class, function (Faker $faker) {
    $refPaymentPeriod = new ReflectionClass(PaymentPeriod::class);
    $payment_periods =  collect(array_values($refPaymentPeriod->getConstants()));

    $uuid = $faker->uuid;
    $title = $faker->sentence(3);
    $poster_image = "https://picsum.photos/id/" . $faker->numberBetween(1, 250) . "/800/400";

    return [
        'id' => $uuid,
        'class_room_id' => factory(ClassRoom::class)->create()->id,
        'slug' => Str::slug($title) . "-" . substr($uuid, 0, 8),
        'title' => $title,
        'description' => $faker->paragraph,
        'is_featured' => $faker->boolean,
        'poster_image_path' => $poster_image,
        'starts_at' => $faker->dateTimeBetween('-1 week', 'now'),
        'ends_at' => $faker->dateTimeBetween('+1 week', '+1 month'),
        'quota' => $faker->numberBetween(10, 50),
        'price' => $faker->numberBetween(100, 500),
        'payment_period' => $payment_periods->random(),
    ];
});

$factory->define(Application::class, function (Faker $faker) {
    $refApplicationStatus == new ReflectionClass(ApplicationStatus::class);
    $status = collect(array_values($refApplicationStatus->getConstants()));

    return [
        'id' => $faker->uuid,
        'student_id' => factory(Student::class)->create()->id,
        'announcement_id' => factory(Announcement::class)->create()->id,
        'status' => $status->random(),
    ];
});
