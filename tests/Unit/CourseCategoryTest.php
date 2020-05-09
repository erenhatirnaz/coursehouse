<?php

namespace Tests\Unit;

use App\Course;
use Tests\TestCase;
use App\CourseCategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CourseCategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testItShouldBeHasManyCourses()
    {
        $courseCategory = factory(CourseCategory::class)->create();

        $course1 = factory(Course::class)->create([
            'course_category_id' => $courseCategory->id,
        ]);
        $course2 = factory(Course::class)->create([
            'course_category_id' => $courseCategory->id,
        ]);

        $this->assertTrue($courseCategory->courses()->exists());
        $this->assertEquals($course1->id, $courseCategory->courses[0]->id);
        $this->assertEquals($course2->id, $courseCategory->courses[1]->id);
    }
}
