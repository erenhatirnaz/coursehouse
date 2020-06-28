<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangeLanguageTest extends TestCase
{
    use RefreshDatabase;

    public function testItShouldChangeLocale()
    {
        app()->setLocale("en");

        $response = $this->get("/switchLocale?lang=tr");
        $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertEquals("tr", app()->getLocale());
    }

    public function testItShouldRedirectToHomePageIfLangIsInvalid()
    {
        app()->setLocale("en");

        $response = $this->get("/switchLocale?lang=foobar");
        $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertEquals("en", app()->getLocale());
    }
}
