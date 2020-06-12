<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {model-name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Repository';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modelName = trim($this->argument('model-name'));

        $interStub = $this->files->get(__DIR__ . '/../Stubs/repository_interface.stub');
        $repoStub = $this->files->get(__DIR__ . '/../Stubs/repository.stub');

        $repoInterName = "{$modelName}RepositoryInterface";
        $repoName = "{$modelName}Repository";

        $interStub = str_replace('MY_MODEL', $modelName, $interStub);
        $interStub = str_replace('MY_REPOSITORY_INTERFACE', $repoInterName, $interStub);

        $repoStub = str_replace('MY_MODEL', $modelName, $repoStub);
        $repoStub = str_replace('MY_REPOSITORY_INTERFACE', $repoInterName, $repoStub);
        $repoStub = str_replace('MY_REPOSITORY', $repoName, $repoStub);

        $this->files->put(app_path('/Repositories/') . "${repoName}.php", $repoStub);
        $this->files->put(app_path('/Repositories/') . "${repoInterName}.php", $interStub);

        $this->info("{$repoName} repository created for {$modelName} model!");

        return true;
    }
}
