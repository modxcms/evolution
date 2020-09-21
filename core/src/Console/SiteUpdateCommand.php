<?php namespace EvolutionCMS\Console;

use EvolutionCMS\Facades\Console;
use Composer\Console\Application;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * @see: https://github.com/laravel-zero/foundation/blob/5.6/src/Illuminate/Foundation/Console/ClearCompiledCommand.php
 */
class SiteUpdateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'make:site {command_site=update}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update site';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->argument('command_site')) {
            case 'pizdato':
                echo 'Remove MODX REVO and install Evolution CMS' . "\n";
                $this->startUpdate();
                break;
            case 'update':
                $this->startUpdate();
                break;
        }
    }

    public function startUpdate()
    {

        $url = 'https://github.com/evolution-cms/evolution/archive/2.1.x.zip';
        echo "Start download EvolutionCMS\n";
        $url = file_get_contents($url);
        $file = MODX_BASE_PATH.'new_version.zip';

        file_put_contents($file, $url);
        echo "Start unpacking EvolutionCMS\n";


        $temp_dir = MODX_BASE_PATH.'_temp'.md5(time());
        //run unzip and install

        $zip = new \ZipArchive;
        $res = $zip->open($file);
        $zip->extractTo($temp_dir);
        $zip->close();
        unlink($file);

        if ($handle = opendir($temp_dir)) {
            while (false !== ($name = readdir($handle))) {
                if ($name != '.' && $name != '..') $dir = $name;
            }
            closedir($handle);
        }

        SELF::moveFiles($temp_dir.'/'.$dir, MODX_BASE_PATH);
        SELF::rmdirs($temp_dir);
        echo "Run Migrations\n";

        exec('php  ../install/cli-install.php --typeInstall=2 --removeInstall=y');
        echo "Remove Install Directory\n";
        self::rmdirs(MODX_BASE_PATH.'install');
        putenv('COMPOSER_HOME=' . EVO_CORE_PATH . 'composer');
        $input = new ArrayInput(array('command' => 'update'));
        $application = new Application();
        $application->setAutoExit(false);
        $application->run($input);
    }
    static public function moveFiles($src, $dest) {
        $path = realpath($src);
        $dest = realpath($dest);
        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
        foreach($objects as $name => $object) {
            $startsAt = substr(dirname($name), strlen($path));
            self::mmkDir($dest.$startsAt);
            if ( $object->isDir() ) {
                self::mmkDir($dest.substr($name, strlen($path)));
            }

            if(is_writable($dest.$startsAt) && $object->isFile()) {
                rename((string)$name, $dest.$startsAt.'/'.basename($name));
            }
        }
    }

    static public function rmdirs($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object) && !is_link($dir."/".$object))
                        self::rmdirs($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
            rmdir($dir);
        }
    }
    static public function mmkDir($folder, $perm=0777) {
        if(!is_dir($folder)) {
            mkdir($folder, $perm);
        }
    }
}
