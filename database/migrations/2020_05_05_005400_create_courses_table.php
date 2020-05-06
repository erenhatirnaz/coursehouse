<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_path')->default("default.png");
            $table->integer('course_category_id')->nullable();
            $table->timestamps();
        });

        Schema::create('course_categories', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('course_teacher', function(Blueprint $table) {
            $table->integer('course_id');
            $table->integer('user_id');
        });

        Schema::create('course_organizer', function(Blueprint $table) {
            $table->integer('course_id');
            $table->integer('user_id');
        });

        $course_categories = ['Programming', 'Music', 'Language', 'Art', 'Culture', 'Other'];
        foreach ($course_categories as $category) {
            DB::table('course_categories')->insert([
                'slug' => Str::slug($category),
                'name' => $category
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('courses');
        Schema::dropIfExists('course_categories');
        Schema::dropIfExists('course_teacher');
        Schema::dropIfExists('course_organizer');
    }
}
