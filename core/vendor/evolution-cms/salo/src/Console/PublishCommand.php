<?php

namespace EvolutionCMS\Salo\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salo:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the Laravel Salo Docker files';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'salo']);

        file_put_contents(
            $this->laravel->basePath('docker-compose.yml'),
            str_replace(
                [
                    './vendor/laravel/salo/runtimes/8.0',
                    './vendor/laravel/salo/runtimes/7.4',
                ],
                [
                    './docker/8.0',
                    './docker/7.4',
                ],
                file_get_contents($this->laravel->basePath('docker-compose.yml'))
            )
        );
    }
}
