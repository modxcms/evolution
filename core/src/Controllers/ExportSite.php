<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Interfaces\ManagerTheme;
use EvolutionCMS\Interfaces\ManagerThemeInterface;
use EvolutionCMS\Models;

class ExportSite extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.export_site';

    /**
     * @var \EvolutionCMS\Interfaces\DatabaseInterface
     */
    protected $database;

    public function __construct(ManagerThemeInterface $managerTheme, array $data = [])
    {
        parent::__construct($managerTheme, $data);
        $this->database = $this->managerTheme->getCore()->getDatabase();
    }

    /**
     * {@inheritdoc}
     */
    public function checkLocked(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function canView(): bool
    {
        return $this->managerTheme->getCore()->hasPermission('export_static');
    }

    public function process() : bool
    {
        $this->parameters['output'] = '';

        if (isset($_POST['export'])) {
            $this->setMaxTime();
            $exportDir = $this->getExportDir();
            if (($output = $this->validateExportDir($exportDir)) === null) {
                $exportSite = $this->objectExportSite($exportDir);

                $output = sprintf($this->managerTheme->getLang('export_site_numberdocs'), $exportSite->total);
                $output .= $exportSite->run();
                $output .= sprintf(
                    '<p>' . $this->managerTheme->getLang("export_site_time") . '</p>',
                    round($exportSite->get_mtime() - $exportSite->exportstart, 3)
                );
            }
            $this->parameters['output'] = $output;
        }

        return true;
    }

    protected function setMaxTime() : int
    {
        $maxTime = (int)get_by_key($_POST, 'maxtime', 30, function ($val) {
            return (int)$val > 0;
        });
        @set_time_limit($maxTime);

        return $maxTime;
    }

    protected function getExportDir() : string
    {
        if (is_dir(MODX_BASE_PATH . 'temp')) {
            $exportDir = MODX_BASE_PATH . 'temp/export';
        } else {
            $exportDir = MODX_BASE_PATH . 'assets/export';
        }

        return $exportDir;
    }

    /**
     * @param string $exportDir
     * @return string|null
     */
    protected function validateExportDir(string $exportDir) : ?string
    {
        $out = null;
        if (strpos(MODX_BASE_PATH, "{$exportDir}/") === 0 &&
            0 <= \strlen(str_replace("{$exportDir}/", '', MODX_BASE_PATH))
        ) {
            $out = $this->managerTheme->getLang('export_site.static.php6');
        } elseif ($this->managerTheme->getCore()->getConfig('rb_base_dir') === $exportDir . '/') {
            $out = $this->managerTheme->getCore()->parsePlaceholder(
                $this->managerTheme->getLang('export_site.static.php7'),
                'rb_base_url=' . MODX_BASE_URL . $this->managerTheme->getCore()->getConfig('rb_base_url')
            );
        } elseif (!is_writable($exportDir)) {
            $out = $this->managerTheme->getLang('export_site_target_unwritable');
        }

        return $out;
    }

    protected function objectExportSite(string $exportDir) : \EvolutionCMS\Legacy\ExportSite
    {
        /** @var \EvolutionCMS\Legacy\ExportSite $exportSite */
        $exportSite = $this->managerTheme->getCore()->getExportSite();

        $exportSite->generate_mode = get_by_key($_POST, 'generate_mode', '');

        $exportSite->setExportDir($exportDir);
        $exportSite->removeDirectoryAll($exportDir);

        $total = $exportSite->getTotal(
            get_by_key($_POST, 'ignore_ids', ''),
            $this->managerTheme->getCore()->getConfig('export_includenoncache')
        );

        $exportSite->total = $total;

        $exportSite->repl_before = get_by_key($_POST, 'repl_before');
        $exportSite->repl_after  = get_by_key($_POST, 'repl_after');

        return $exportSite;
    }
}
