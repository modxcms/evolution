<?php namespace EvolutionCMS\Interfaces;

interface ManagerController
{
    public function __construct(ManagerThemeInterface $managerTheme);

    public function render() : string;

    public function canView() : bool;

    public function checkLocked() : ?string;
}
