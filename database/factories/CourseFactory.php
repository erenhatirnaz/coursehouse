<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use App\ClassRoom;
use App\LessonPeriod;
use App\CourseCategory;
use Faker\Generator as Faker;

$factory->define(CourseCategory::class, function (Faker $faker) {
    $name = $faker->word . " " . $faker->word;

    return [
        'slug' => Str::slug($name),
        'name' => $name,
    ];
});

$factory->define(Course::class, function (Faker $faker) {
    $name = $faker->sentence(2);
    $image = "https://picsum.photos/id/" . $faker->numberBetween(1, 250) . "/300/150";

    return [
        'slug' => Str::slug($name),
        'name' => $name,
        'description' => $faker->paragraph,
        'image_path' => $image,
        'course_category_id' => factory(CourseCategory::class)->create()->id,
    ];
});

$factory->define(ClassRoom::class, function (Faker $faker) {
    $refLessonPeriod = new ReflectionClass(LessonPeriod::class);
    $lesson_periods = collect(array_values($refLessonPeriod->getConstants()));
    $grades = collect(['Beginner', 'Intermediate', 'Expert']);

    $name = $grades->random() . " "
          . $faker->numberBetween(1, 5) . "/"
          . strtoupper($faker->randomLetter);

    return [
        'slug' => Str::slug($name),
        'course_id' => factory(Course::class)->create()->id,
        'name' => $name,
        'description' => $faker->paragraph,
        'age_range_min' => $faker->numberBetween(5, 18),
        'age_range_max' => $faker->numberBetween(7, 60),
        'quota' => $faker->numberBetween(5, 50),
        'lesson_period' => $lesson_periods->random(),
    ];
});
