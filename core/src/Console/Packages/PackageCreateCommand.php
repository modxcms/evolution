<?php namespace EvolutionCMS\Console\Packages;

use Illuminate\Console\Command;
use \EvolutionCMS;
use Illuminate\Support\Facades\File;

class PackageCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:create {packagename?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install composer package';

    /**
     * Custom composer.json
     * @var string
     */
    protected $composer = EVO_CORE_PATH . 'custom/composer.json';

    /**
     * @var string
     */
    protected $namePackage = '';

    /**
     * @var string
     */
    protected $directory = '';
    /**
     * @var string
     */
    protected $file = 'https://github.com/evolution-cms/evoPackage/archive/master.zip';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->argument('packagename') != '') {
            $this->namePackage = $this->argument('packagename');
            $this->checkPath();
        } else {
            $this->enterName();
        }
        $data = file_get_contents($this->file);
        file_put_contents($this->directory . '/temp.zip', $data);
        $zip = new \ZipArchive;
        if ($zip->open($this->directory . '/temp.zip') == true) {
            $zip->extractTo($this->directory . '/');
            $zip->close();
            File::copyDirectory($this->directory . '/evoPackage-master/', $this->directory . '/');
            File::deleteDirectory($this->directory . '/evoPackage-master/');
            unlink($this->directory . '/temp.zip');
            $serviceprovider = file_get_contents($this->directory . '/src/ExampleServiceProvider.php');
            $serviceprovider = str_replace('example', $this->namePackage, $serviceprovider);
            $serviceprovider = str_replace('Example', ucfirst($this->namePackage), $serviceprovider);
            file_put_contents($this->directory . '/src/' . ucfirst($this->namePackage) . 'ServiceProvider.php', $serviceprovider);
            //update composer part
            $composer = file_get_contents($this->directory . '/src/composer.json');
            $composer = str_replace('example', $this->namePackage, $composer);
            $composer = str_replace('Example', ucfirst($this->namePackage), $composer);
            file_put_contents($this->directory . '/src/composer.json', $composer);

            unlink($this->directory . '/src/ExampleServiceProvider.php');
            $dirForComposer = 'packages/' . $this->namePackage . '/src/';
            $namespaceForComposer = 'EvolutionCMS\\' . ucfirst($this->namePackage) . '\\';
            $this->call('package:installautoload', ['key' => $namespaceForComposer, 'value' => $dirForComposer]);
        }
    }

    public function enterName($message = '')
    {
        $this->namePackage = $this->ask($message . 'Enter u package name: ');
        $this->namePackage = strtolower($this->namePackage);
        $this->checkPath();
    }

    public function checkPath()
    {
        $this->directory = EVO_CORE_PATH . 'custom/packages/' . $this->namePackage;
        if (!File::isDirectory($this->directory)) {
            File::makeDirectory($this->directory, 0755, true);
        } else {
            $this->enterName('This package name already used. Please enter other name.' . "\n");
        }
    }



}
