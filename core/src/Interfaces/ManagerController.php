<?php namespace EvolutionCMS\Interfaces;

interface ManagerController
{
    public function __construct(ManagerThemeInterface $managerTheme);

    public function getView() : string;

    public function canView() : bool;

    public function checkLocked() : ?string;

    public function getParameters() : array;

    public function render() : string;
}
