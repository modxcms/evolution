<?php namespace EvolutionCMS\Interfaces\ManagerTheme;

interface TabControllerInterface extends ControllerInterface
{
    public function getTabName($withIndex = true) : string;
}
