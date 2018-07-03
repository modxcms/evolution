<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

class SiteSchedule extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.site_schedule';

    /**
     * @inheritdoc
     */
    public function checkLocked() : ?string
    {
        $out = Models\ActiveUser::locked(70)
            ->first();
        if ($out !== null) {
            $out = sprintf($this->managerTheme->getLexicon('error_no_privileges'), $out->username);
        }

        return $out;
    }

    /**
     * @inheritdoc
     */
    public function canView(): bool
    {
        return evolutionCMS()->hasPermission('site_schedule');
    }

    public function getParameters(array $params = []) : array
    {
        return [
            'test' => 1
        ];
    }
}
