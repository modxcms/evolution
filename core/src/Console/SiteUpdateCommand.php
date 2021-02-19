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
    protected $signature = 'make:site {command_site=update} {version=null}';
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
        $evo = EvolutionCMS();
        $updateRepository = $evo->getConfig('UpgradeRepository');
        if ($updateRepository == '') {
            $updateRepository = 'evolution-cms/evolution';
        }
        $ch = curl_init();
        $url = 'https://api.github.com/repos/' . $updateRepository . '/tags';
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: updateNotify widget'));
        $info = curl_exec($ch);
        curl_close($ch);
        if (substr($info, 0, 1) != '[') {
            return;
        }
        $currentVersion = $evo->getVersionData();
        $arrayVersion = explode('.', $currentVersion['version']);
        $currentMajorVersion = array_shift($arrayVersion);

        $info = json_decode($info, true);
        foreach ($info as $key => $val) {

            $arrayVersion = explode('.', $val['name']);
            if ($currentMajorVersion == array_shift($arrayVersion)) {

                $git['version'] = $val['name'];

                if (strpos($val['name'], 'alpha')) {
                    $git['alpha'] = $val['name'];
                    continue;
                } elseif (strpos($val['name'], 'beta')) {
                    $git['beta'] = $val['name'];
                    continue;
                } else {
                    $git['stable'] = $val['name'];
                    break;
                }
            }
        }
        $git['version'] = $this->argument('version');

        if ($git['version'] == 'null') {
            if (isset($git['stable'])) {
                if (version_compare($currentVersion['version'], $git['stable'], '!=')) {
                    $git['version'] = $git['stable'];
                }
            }
        }
        if ($git['version'] != '') {
            $url = 'https://github.com/evolution-cms/evolution/archive/' . $git['version'] . '.zip';
            echo "Start download EvolutionCMS\n";
            $url = file_get_contents($url);
            $file = MODX_BASE_PATH . 'new_version.zip';

            file_put_contents($file, $url);
            echo "Start unpacking EvolutionCMS\n";

            $temp_dir = MODX_BASE_PATH . '_temp' . md5(time());
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

            SELF::moveFiles($temp_dir . '/' . $dir, MODX_BASE_PATH);
            SELF::rmdirs($temp_dir);

            $delete_file = MODX_BASE_PATH . 'install/stubs/file_for_delete.txt';
            if (file_exists($delete_file)) {
                $files = explode("\n", file_get_contents($delete_file));
                foreach ($files as $file) {
                    $file = str_replace('{core}', EVO_CORE_PATH, $file);
                    if (file_exists($file)) {
                        if (is_dir($file)) {
                            SELF::rmdirs($file);
                        } else {
                            unlink($file);
                        }
                    }
                }
            }
            putenv('COMPOSER_HOME=' . EVO_CORE_PATH . 'composer');
            $input = new ArrayInput(array('command' => 'update'));
            $application = new Application();
            $application->setAutoExit(false);
            $application->run($input);
            echo "Run Migrations\n";

            exec('php  ../install/cli-install.php --typeInstall=2 --removeInstall=y');
            echo "Remove Install Directory\n";
            self::rmdirs(MODX_BASE_PATH . 'install');
        } else {
            echo 'You use almost current version';
        }
    }

    static public function moveFiles($src, $dest)
    {
        $path = realpath($src);
        $dest = realpath($dest);
        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($objects as $name => $object) {
            $startsAt = substr(dirname($name), strlen($path));
            self::mmkDir($dest . $startsAt);
            if ($object->isDir()) {
                self::mmkDir($dest . substr($name, strlen($path)));
            }

            if (is_writable($dest . $startsAt) && $object->isFile()) {
                rename((string)$name, $dest . $startsAt . '/' . basename($name));
            }
        }
    }

    static public function rmdirs($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object) && !is_link($dir . "/" . $object))
                        self::rmdirs($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            rmdir($dir);
        }
    }

    static public function mmkDir($folder, $perm = 0777)
    {
        if (!is_dir($folder)) {
            mkdir($folder, $perm);
        }
    }
}
