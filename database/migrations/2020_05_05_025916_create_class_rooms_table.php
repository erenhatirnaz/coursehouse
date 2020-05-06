<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassRoomsTable extends Migration
{
    public function up()
    {
        Schema::create('class_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->integer('age_range_min');
            $table->integer('age_range_max');
            $table->integer('quota');
            $table->enum('lesson_period', ['daily', 'weekly', 'monthly']);
            $table->timestamps();
        });

        Schema::create('class_room_student', function (Blueprint $table) {
            $table->integer('class_room_id');
            $table->integer('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_rooms');
        Schema::dropIfExists('class_room_student');
    }
}
