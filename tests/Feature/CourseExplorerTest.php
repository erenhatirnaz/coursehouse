<?php

namespace Tests\Feature;

use App\Course;
use App\Teacher;
use App\ClassRoom;
use Tests\TestCase;
use App\CourseCategory;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseExlorerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $test = $this;

        // macro source: https://github.com/laravel/framework/issues/18016#issuecomment-322401713
        TestResponse::macro('followRedirects', function ($testCase = null) use ($test) {
            $response = $this;
            $testCase = $testCase ?: $test;

            while ($response->isRedirect()) {
                $response = $testCase->get($response->headers->get('Location'));
            }

            return $response;
        });
    }

    public function testPageShouldReturnHttpOkCode()
    {
        $response = $this->get('/course/explorer');

        $response->assertStatus(200);
    }

    public function testAllCategoriesShouldBeListedInFiltersSection()
    {
        $courseCategories = factory(CourseCategory::class, 4)->create();

        $courseCategories->each(function ($courseCategory) {
            factory(Course::class)->create(['course_category_id' => $courseCategory->id]);
        });

        $response = $this->get("/course/explorer");

        $response->assertStatus(200);
        $response->assertSeeText($courseCategories[0]->name);
        $response->assertSeeText($courseCategories[1]->name);
        $response->assertSeeText($courseCategories[2]->name);
        $response->assertSeeText($courseCategories[3]->name);
        $response->assertDontSeeText("foobar");
    }

    public function testAllTeachersShouldBeListedInFiltersSection()
    {
        $teachers = factory(Teacher::class, 4)->create();
        $teachers->each(function ($teacher) {
            $teacher->courses()->save(factory(Course::class)->make());
        });

        $response = $this->get("/course/explorer");

        $response->assertStatus(200);
        $response->assertSeeText($teachers[0]->full_name);
        $response->assertSeeText($teachers[1]->full_name);
        $response->assertSeeText($teachers[2]->full_name);
        $response->assertSeeText($teachers[3]->full_name);
        $response->assertDontSeeText("foobarbaz");
    }

    public function testCategoryFilterShouldBeApplied()
    {
        $courseCategories = factory(CourseCategory::class, 2)->create();

        $mainCategory = $courseCategories[0];
        $secondaryCategory = $courseCategories[1];

        $mainCourses = factory(Course::class, 2)->create(['course_category_id' => $mainCategory->id]);
        $secondaryCourses = factory(Course::class, 3)->create(['course_category_id' => $secondaryCategory->id]);

        $response = $this->get("/course/explorer?category={$mainCategory->slug}&status=all");

        $response->assertStatus(200);
        $response->assertSeeText("2 courses were found that fit your filters!");
        $response->assertSeeText($mainCourses[0]->name);
        $response->assertDontSeeText($secondaryCourses[0]->name);
    }

    public function testPageShouldShowErrorMessageIfGivenCategoryFilterIsInvalid()
    {
        $response = $this->get('/course/explorer?category=foobar&status=all', [
            'HTTP_REFERER' => "/course/explorer",
        ])->assertRedirect('/course/explorer');

        $response->assertStatus(302);
        $response->followRedirects($this)
                 ->assertSeeText("The selected Category is invalid.");
    }

    public function testTeacherFilterShouldBeApplied()
    {
        $mainCourse = factory(Course::class)->create();
        $secondaryCourse = factory(Course::class)->create();

        $mainTeachers = factory(Teacher::class, 2)->create();
        $secondaryTeachers = factory(Teacher::class, 3)->create();

        $mainTeachers->each(function ($mainTeacher) use ($mainCourse) {
            $mainTeacher->courses()->attach($mainCourse->id);
        });

        $secondaryTeachers->each(function ($secondaryTeacher) use ($secondaryCourse) {
            $secondaryTeacher->courses()->attach($secondaryCourse->id);
        });

        $response = $this->get("/course/explorer?category=all&teacher[]={$mainTeachers[0]->id}&status=all");

        $response->assertStatus(200);
        $response->assertSeeText("1 courses were found that fit your filters!");
        $response->assertSeeText($mainCourse->name);
        $response->assertSeeText($mainTeachers[0]->full_name);
        $response->assertSeeText($mainTeachers[1]->full_name);
        $response->assertDontSeeText($secondaryCourse->name);
    }

    public function testPageShouldShowErrorMessageIfGivenTeacherFilterIsInvalid()
    {
        $response = $this->get('/course/explorer?category=all&teacher[]=123&status=all', [
            'HTTP_REFERER' => "/course/explorer",
        ])->assertRedirect("/course/explorer");

        $response->assertStatus(302);
        $response->followRedirects($this)
                 ->assertSeeText("The selected Teacher(s) is invalid.");
    }

    public function testStatusFilterShouldBeApplied()
    {
        $mainCourse = factory(Course::class)->create();
        $secondaryCourse = factory(Course::class)->create();

        factory(ClassRoom::class)->create(['course_id' => $mainCourse->id]);

        $response = $this->get("/course/explorer?category=all&status=active");

        $response->assertStatus(200);
        $response->assertSeeText("1 courses were found that fit your filters!");
        $response->assertSeeText($mainCourse->name);
        $response->assertDontSeeText($secondaryCourse->name);
    }

    public function testPageShouldShowErrorMessageIfGivenStatusFilterIsInvalid()
    {
        $response = $this->get('/course/explorer?category=all&status=foobar', [
            'HTTP_REFERER' => "/course/explorer",
        ])->assertRedirect("/course/explorer");

        $response->assertStatus(302);
        $response->followRedirects($this)
                 ->assertSeeText("The selected Status is invalid.");
    }
}
