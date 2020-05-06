<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->uuid('id');
            $table->integer('class_room_id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('poster_image_path')->default('default.png')->nullable();
            $table->datetime('starts_at');
            $table->datetime('ends_at');
            $table->integer('quota')->default(0);
            $table->double('price');
            $table->enum('payment_period', ['one_time', 'daily', 'weekly', 'monthly', 'yearly']);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('announcements');
    }
}
