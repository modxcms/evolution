<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Interfaces\ManagerController;
use EvolutionCMS\Interfaces\ManagerThemeInterface;

abstract class AbstractController implements ManagerController
{
    protected $view;

    /**
     * @var ManagerThemeInterface
     */
    protected $managerTheme;

    public function __construct(ManagerThemeInterface $managerTheme)
    {
        $this->managerTheme = $managerTheme;
    }

    public function getView(): string
    {
        return $this->view;
    }

    abstract public function canView(): bool;

    abstract public function checkLocked() : ?string;

    abstract public function getParameters() : array;

    public function render() : string
    {
        return $this->managerTheme->view(
            $this->getView(),
            $this->getParameters()
        )->render();
    }
}
