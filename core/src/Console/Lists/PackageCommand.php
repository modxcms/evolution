<?php namespace EvolutionCMS\Console\Lists;

use Illuminate\Console\Command;

class PackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:discover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate ServiceProviders for custom packages';
    /**
     * Path for custom providers
     * @var string
     */
    protected $configDir = EVO_CORE_PATH . 'custom/config/app/providers/';
    /**
     * Custom composer.json
     * @var string
     */
    protected $composer = EVO_CORE_PATH . 'custom/composer.json';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!is_dir($this->configDir)) {
            mkdir($this->configDir, 'rwxrwxr-x', true);
        }
        if (!is_dir($this->configDir)) {
            $this->getOutput()->write('<error>ERROR CREATE CONFIG DIR</error>');
            exit();
        }

        if (file_exists($this->composer)) {
            $this->parseComposer($this->composer);
        }
    }

    public function parseComposer($composer)
    {
        $data = json_decode(file_get_contents($composer), true);
        if (isset($data['extra']['laravel']['providers'])) {
            foreach ($data['extra']['laravel']['providers'] as $value) {
                $this->process($value);
            }
        }
        if (isset($data['require'])) {
            foreach ($data['require'] as $key => $value) {
                $composer = EVO_CORE_PATH . 'vendor/' . $key . '/composer.json';

                if (file_exists($composer)) {
                    $this->parseComposerServiceProvider($composer);
                }
            }
        }
        if (isset($data['autoload']['psr-4'])) {
            foreach ($data['autoload']['psr-4'] as $key => $value) {
                $composer = EVO_CORE_PATH . 'custom/' . $value . '/composer.json';
                if (file_exists($composer)) {
                    $this->parseComposerServiceProvider($composer);
                }

            }
        }
    }

    public function parseComposerServiceProvider($composer)
    {

        $data = json_decode(file_get_contents($composer), true);
        if (isset($data['extra']['laravel']['providers'])) {
            foreach ($data['extra']['laravel']['providers'] as $value) {
                $this->process($value);
            }
        }
    }

    protected function process($value)
    {
        $fileName = end(explode('\\', $value)) . '.php';
        $fileContent = "<?php \nreturn " . $value . "::class;";
        file_put_contents($this->configDir . $fileName, $fileContent);
        /*$this->getOutput()->write(
            sprintf(
                '<info>%s</info>',
                $value
            )
        );*/

    }
}
