<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

class SiteSchedule extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.site_schedule';

    /**
     * @inheritdoc
     */
    public function checkLocked(): ?string
    {
        $out = Models\ActiveUser::locked(70)->first();
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
        return evolutionCMS()->hasPermission('view_eventlog');
    }

    /**
     * @inheritdoc
     */
    public function getParameters(array $params = []): array
    {
        return [
            'publishedDocs' => Models\SiteContent::where('pub_date', '>', time())->orderBy('pub_date', 'asc')->get(),
            'unpublishedDocs' => Models\SiteContent::where('pub_date', '>', time())->orderBy('unpub_date', 'asc')->get(),
            'allDocs' => Models\SiteContent::whereRaw('pub_date > 0 OR unpub_date > 0')->orderBy('pub_date', 'desc')->get(),
            'server_offset_time' => get_by_key(evolutionCMS()->config, 'server_offset_time')
        ];
    }
}
