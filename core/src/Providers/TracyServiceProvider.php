<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Tracy\Debugger;
use EvolutionCMS\Interfaces\TracyPanel;
use Tracy\IBarPanel;

class TracyServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if ($this->isTracyHandler()) {
            $this->activateTracy();
        }
    }

    protected function activateTracy() : void
    {
        Debugger::enable($this->isHiddenTracyHandler(), $this->logPath());
        Debugger::$strictMode = $this->isStrictMode();
        Debugger::$showLocation = $this->isShowLocation();

        $this->registerErrorTpl();
        $this->registerPanels($this->listPanels());
    }

    protected function logPath() : string
    {
        return evolutionCMS()->storagePath() . '/logs';
    }

    protected function isTracyHandler() : bool
    {
        $this->prepareActiveTracy();
        return $this->app['config']->get('tracy.active');
    }

    /**
     * @return mixed
     */
    public function isHiddenTracyHandler()
    {
        return $this->app['config']->get('tracy.hidden');
    }

    protected function isShowLocation() : bool
    {
        return true;
    }

    protected function isStrictMode() : bool
    {
        return false;
    }

    protected function registerErrorTpl() : void
    {
        $errorTpl = $this->app['config']->get('tracy.error.500');
        if ($errorTpl !== null) {
            Debugger::$errorTemplate = MODX_BASE_PATH . $errorTpl;
        }
    }

    protected function prepareActiveTracy() : void
    {
        $flag = $this->app['config']->get('tracy.active');
        if (\is_string($flag)) {
            $this->app['config']->set(
                'tracy.active',
                $flag === 'manager' && $this->app->isLoggedIn('mgr')
            );
        }
    }

    protected function listPanels() : array
    {
        $panels = $this->app['config']->get('tracy.panels');

        return \is_array($panels) ? $panels : [];
    }

    protected function registerPanels(array $panels) : void
    {
        foreach ($panels as $panel) {
            $this->injectPanel(new $panel);
        }
    }

    protected function injectPanel(IBarPanel $panel) : void
    {
        if (is_a($panel, TracyPanel::class)) {
            $panel->setEvolutionCMS($this->app);
        }
        Debugger::getBar()->addPanel($panel);
    }
}
