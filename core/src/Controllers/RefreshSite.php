<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Interfaces\ManagerTheme;
use EvolutionCMS\Interfaces\ManagerThemeInterface;
use EvolutionCMS\Models\SiteContent;
use Illuminate\Database\Eloquent\Builder;

class RefreshSite extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.refresh_site';

    /**
     * @var \EvolutionCMS\Interfaces\DatabaseInterface
     */
    protected $database;

    public function __construct(ManagerThemeInterface $managerTheme)
    {
        parent::__construct($managerTheme);
        $this->database = evolutionCMS()->getDatabase();
    }

    /**
     * @inheritdoc
     */
    public function checkLocked(): ?string
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function canView(): bool
    {
        return true;
    }

    public function process() : bool
    {
        // (un)publishing of documents, version 2!
        // first, publish document waiting to be published
        $time = (int)$_SERVER['REQUEST_TIME'];

        $this->parameters = [
            'num_rows_pub' => $this->publishDocuments($time),
            'num_rows_unpub' => $this->unPublishDocuments($time),
        ];

        ob_start();
            evolutionCMS()->clearCache('full', true);
            $this->parameters['cache_log'] = ob_get_contents();
        ob_end_clean();

        // invoke OnSiteRefresh event
        evolutionCMS()->invokeEvent("OnSiteRefresh");

        return true;
    }

    protected function publishDocuments(int $time) : int
    {
        $query = SiteContent::publishDocuments($time);

        $count = $query->count();

        $query->update(['published' => 1]);

        return $count;
    }

    protected function unPublishDocuments(int $time) : int
    {
        $query = SiteContent::unPublishDocuments($time);

        $count = $query->count();

        $query->update(['published' => 0]);

        return $count;
    }
}
