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
        /** @var \Illuminate\Database\Eloquent\Collection $pub */
        $pub = Models\SiteContent::where('pub_date', '>', evolutionCMS()->timestamp())
            ->orderBy('pub_date', 'asc')
            ->get();

        /** @var \Illuminate\Database\Eloquent\Collection $unPub */
        $unPub = Models\SiteContent::where('unpub_date', '>', evolutionCMS()->timestamp())
            ->orderBy('unpub_date', 'asc')
            ->get();

        return [
            'publishedDocs' => $pub,
            'unpublishedDocs' => $unPub,
            'allDocs' => $pub->merge($unPub)
        ];
    }
}
