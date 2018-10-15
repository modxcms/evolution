<?php namespace EvolutionCMS\Interfaces;

interface ManagerThemeInterface
{
    public function getCore() : CoreInterface;

    public function getLang() : string;

    public function getStyle($key = null);
}
