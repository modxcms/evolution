<?php namespace EvolutionCMS\Legacy;

use EvolutionCMS\Interfaces\ExportSiteInerface;
use EvolutionCMS\Models;
use Illuminate\Database\Eloquent;
use UrlProcessor;

class ExportSite implements ExportSiteInerface
{
    /**
     * @var string
     */
    public $targetDir;
    /**
     * @var string
     */
    public $generate_mode;
    /**
     * @var
     */
    public $total;
    /**
     * @var int
     */
    public $count;
    /**
     * @var array
     */
    public $ignore_ids;
    /**
     * @var array|mixed
     */
    public $exportstart;
    /**
     * @var
     */
    public $repl_before;
    /**
     * @var
     */
    public $repl_after;
    /**
     * @var array
     */
    public $output = array();
    /**
     * @var int
     */
    public $dirCheckCount = 0;

    /**
     * EXPORT_SITE constructor.
     */
    public function __construct()
    {
        $modx = evolutionCMS();

        if (!defined('MODX_BASE_PATH')) {
            return false;
        }
        $this->exportstart = $this->get_mtime();
        $this->count = 0;
        $this->setUrlMode();
        $this->generate_mode = 'crawl';
        $this->targetDir = MODX_BASE_PATH . 'temp/export';
        if (!isset($this->total)) {
            $this->getTotal();
        }
    }

    /**
     * @param string $dir
     */
    public function setExportDir($dir)
    {
        $dir = str_replace('\\', '/', $dir);
        $dir = rtrim($dir, '/');
        $this->targetDir = $dir;
    }

    /**
     * @return int
     */
    public function get_mtime()
    {
        $mtime = microtime();
        $mtime = explode(' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];

        return $mtime;
    }

    /**
     * @return void
     */
    public function setUrlMode()
    {
        $modx = evolutionCMS();

        if ($modx->getConfig('friendly_urls') === false) {
            $modx->setConfig('friendly_urls', true);
            $modx->setConfig('use_alias_path', true);
            $modx->clearCache('full');
        }
        $modx->setConfig('make_folders', true);
    }

    /**
     * @param string|array $ignoreIds
     * @param string|int|bool $noncache
     * @return int
     */
    public function getTotal($ignoreIds = '', $noncache = false)
    {
        $this->ignore_ids = $this->cleanIDs($ignoreIds);
        $this->total = $this->makeQuery($this->ignore_ids, $noncache)->count();
        return $this->total;
    }

    /**
     * @param string|array $ids
     */
    protected function cleanIDs($ids)
    {
        return array_filter(
            array_map('intval', \is_array($ids) ? $ids : explode(',', $ids))
        );
    }

    /**
     * @param string|array $ignoreIds
     * @param bool|int|string $noncache
     * @return Eloquent\Builder
     */
    protected function makeQuery($ignoreIds = '', $noncache = false) : Eloquent\Builder
    {
        /** @var Eloquent\Builder $query */
        $query = Models\SiteContent::where('deleted', '=', 0)
            ->where(function (Eloquent\Builder $query) {
                $query->where(function (Eloquent\Builder $query) {
                    $query->where('published', '=', 1)
                        ->where('type', '=', 'document');
                })->orWhere('isfolder', '=', 1);
            });
        if ((bool)$noncache === false) {
            $query->where('cacheable', '=', 1);
        }

        $ignoreIds = $this->cleanIDs($ignoreIds);
        if (!empty($ignoreIds)) {
            $query->whereNotIn('id', $ignoreIds);
        }

        return $query;
    }
    /**
     * @param string $directory
     * @return bool
     */
    public function removeDirectoryAll($directory = '')
    {
        $rs = false;
        if (empty($directory)) {
            $directory = $this->targetDir;
        }
        $directory = rtrim($directory, '/');
        // if the path is not valid or is not a directory ...
        if (empty($directory)) {
            return false;
        }
        if (strpos($directory, MODX_BASE_PATH) === false) {
            return $rs;
        }

        if (!is_dir($directory)) {
            return $rs;
        } elseif (!is_readable($directory)) {
            return $rs;
        } else {
            $files = glob($directory . '/*');
            if (!empty($files)) {
                foreach ($files as $path) {
                    $rs = is_dir($path) ? $this->removeDirectoryAll($path) : unlink($path);
                }
            }
        }
        if ($directory !== $this->targetDir) {
            $rs = rmdir($directory);
        }

        return $rs;
    }

    /**
     * @param int $docid
     * @param string $filepath
     * @return string
     */
    public function makeFile($docid, $filepath)
    {
        $modx = evolutionCMS(); global $_lang;
        $file_permission = octdec($modx->getConfig('new_file_permissions'));
        if ($this->generate_mode === 'direct') {
            $back_lang = $_lang;
            $src = $modx->executeParser($docid);

            $_lang = $back_lang;
        } else {
            $src = $this->curl_get_contents(MODX_SITE_URL . "index.php?id={$docid}");
        }


        if ($src !== false) {
            if ($this->repl_before !== $this->repl_after) {
                $src = str_replace($this->repl_before, $this->repl_after, $src);
            }
            $result = file_put_contents($filepath, $src);
            if ($result !== false) {
                @chmod($filepath, $file_permission);
            }

            if ($result !== false) {
                return 'success';
            } else {
                return 'failed_no_write';
            }
        } else {
            return 'failed_no_retrieve';
        }
    }

    /**
     * @param int $docid
     * @param string $alias
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function getFileName($docid, $alias = '', $prefix, $suffix)
    {
        $modx = evolutionCMS();

        if ($alias === '') {
            $filename = $prefix . $docid . $suffix;
        } else {
            if ($modx->getConfig('suffix_mode') == '1' && strpos($alias, '.') !== false) {
                $suffix = '';
            }
            $filename = $prefix . $alias . $suffix;
        }

        return $filename;
    }

    /**
     * @param int $parent
     * @return string
     */
    public function run($parent = 0)
    {
        global $_lang;
        $modx = evolutionCMS();

        $ignore_ids = $this->ignore_ids;
        $dirpath = $this->targetDir . '/';

        $prefix = $modx->getConfig('friendly_url_prefix');
        $suffix = $modx->getConfig('friendly_url_suffix');

        $tpl = ' <span class="[+status+]">[+msg1+]</span> [+msg2+]</span>';
        $ph = array();

        $ph['status'] = 'fail';
        $ph['msg1'] = $_lang['export_site_failed'];
        $ph['msg2'] = $_lang["export_site_failed_no_write"] . ' - ' . $dirpath;
        $msg_failed_no_write = $this->parsePlaceholder($tpl, $ph);

        $ph['msg2'] = $_lang["export_site_failed_no_retrieve"];
        $msg_failed_no_retrieve = $this->parsePlaceholder($tpl, $ph);

        $ph['status'] = 'success';
        $ph['msg1'] = $_lang['export_site_success'];
        $ph['msg2'] = '';
        $msg_success = $this->parsePlaceholder($tpl, $ph);

        $ph['msg2'] = $_lang['export_site_success_skip_doc'];
        $msg_success_skip_doc = $this->parsePlaceholder($tpl, $ph);

        $ph['msg2'] = $_lang['export_site_success_skip_dir'];
        $msg_success_skip_dir = $this->parsePlaceholder($tpl, $ph);

        $data = $this->makeQuery($ignore_ids, (bool)get_by_key($_POST, 'includenoncache', false))
            ->where('parent', '=', $parent)
            ->get();

        $ph = array();
        $ph['total'] = $this->total;
        $folder_permission = octdec($modx->getConfig('new_folder_permissions'));
        /** @var Models\SiteContent $item */
        foreach ($data as $item) {
            $this->count++;
            $row = $item->toArray();
            $row['count'] = $this->count;
            $row['url'] = UrlProcessor::makeUrl($row['id']);
            $filename = '';

            if ($item->wasNull === false) { // needs writing a document
                $docname = $this->getFileName($row['id'], $row['alias'], $prefix, $suffix);
                $filename = $dirpath . $docname;
                if (!is_file($filename)) {
                    if ($row['published'] === 1) {
                        $status = $this->makeFile($row['id'], $filename);
                        switch ($status) {
                            case 'failed_no_write'   :
                                $row['status'] = $msg_failed_no_write;
                                break;
                            case 'failed_no_retrieve':
                                $row['status'] = $msg_failed_no_retrieve;
                                break;
                            default:
                                $row['status'] = $msg_success;
                        }
                    } else {
                        $row['status'] = $msg_failed_no_retrieve;
                    }
                } else {
                    $row['status'] = $msg_success_skip_doc;
                }
                $this->output[] = $this->parsePlaceholder($_lang['export_site_exporting_document'], $row);
            } else {
                $row['status'] = $msg_success_skip_dir;
                $this->output[] = $this->parsePlaceholder($_lang['export_site_exporting_document'], $row);
            }
            if ($row['isfolder'] === '1' && ($modx->getConfig('suffix_mode') != '1' || strpos($row['alias'],
                        '.') === false)) { // needs making a folder
                $end_dir = ($row['alias'] !== '') ? $row['alias'] : $row['id'];
                $dir_path = $dirpath . $end_dir;
                if (strpos($dir_path, MODX_BASE_PATH) === false) {
                    return false;
                }
                if (!is_dir($dir_path)) {
                    if (is_file($dir_path)) {
                        @unlink($dir_path);
                    }
                    mkdir($dir_path);
                    @chmod($dir_path, $folder_permission);

                }

                if ($modx->getConfig('make_folders') == '1' && $row['published'] === '1') {
                    if (!empty($filename) && is_file($filename)) {
                        rename($filename, $dir_path . '/index.html');
                    }
                }
                $this->targetDir = $dir_path;
                $this->run($row['id']);
            }
        }

        return implode("\n", $this->output);
    }

    /**
     * @param string $url
     * @param int $timeout
     * @return string
     */
    public function curl_get_contents($url, $timeout = 30)
    {
        if (!function_exists('curl_init')) {
            return @file_get_contents($url);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    // 0 = DO NOT VERIFY AUTHENTICITY OF SSL-CERT
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);    // 2 = CERT MUST INDICATE BEING CONNECTED TO RIGHT SERVER
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function parsePlaceholder($tpl, $ph = array())
    {
        foreach ($ph as $k => $v) {
            $k = "[+{$k}+]";
            $tpl = str_replace($k, $v, $tpl);
        }

        return $tpl;
    }

}
