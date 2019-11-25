<?php namespace EvolutionCMS\Console\Packages;


use \EvolutionCMS;
use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class InstallPackageAutoloadCommand extends InstallPackageRequireCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:installautoload {key} {value} {composer_run=1}';


    public function updateArray()
    {
        $this->composerArray['autoload']['psr-4'][$this->argument('key')] = $this->argument('value');
    }



}
