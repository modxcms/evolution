<?php namespace EvolutionCMS\Interfaces\ManagerTheme;

use EvolutionCMS\Interfaces\ManagerThemeInterface;

interface ControllerInterface
{
    public function __construct(ManagerThemeInterface $managerTheme);

    public function getView() : string;

    public function canView() : bool;

    /**
     * check to see the edit settings page isn't locked
     * @return null|string
     */
    public function checkLocked() : ?string;

    public function getParameters(array $params = []) : array;

    public function process() : bool;

    public function render(array $params = []) : string;

    public function setIndex($index) : void;

    public function getElementId() : int;

    /**
     * @return null|int|string
     */
    public function getIndex();
}
