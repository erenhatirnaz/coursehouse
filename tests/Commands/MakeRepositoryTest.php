<?php

namespace Tests\Commands;

use Tests\TestCase;
use Illuminate\Console\Command;

class MakeRepositoryTest extends TestCase
{
    public function testItShouldGeneratesANewRepository()
    {
        $this->artisan('make:repository', ['model-name' => 'FooBar']);

        $this->assertFileExists(app_path('/Repositories/FooBarRepositoryInterface.php'));
        $this->assertFileExists(app_path('/Repositories/FooBarRepository.php'));

        $this->removeCreatedFile(app_path('/Repositories/FooBarRepositoryInterface.php'));
        $this->removeCreatedFile(app_path('/Repositories/FooBarRepository.php'));
    }

    public function removeCreatedFile($filename)
    {
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
}
