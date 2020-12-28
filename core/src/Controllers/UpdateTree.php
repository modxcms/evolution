<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Interfaces\ManagerTheme;
use EvolutionCMS\Interfaces\ManagerThemeInterface;
use EvolutionCMS\Models;
use EvolutionCMS\Models\ClosureTable;
use EvolutionCMS\Models\SiteContent;

class UpdateTree extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.update_tree';

    /**
     * @var \EvolutionCMS\Interfaces\DatabaseInterface
     */
    protected $database;

    /**
     * {@inheritdoc}
     */
    public function checkLocked(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function canView(): bool
    {
        return true;
    }

    public function process() : bool
    {
        $this->parameters['count'] = Models\SiteContent::query()->count();

        $this->parameters['finish'] = 0;
        if(isset($_POST['start'])){
            $start = microtime(true);
            $result = SiteContent::query()->where('parent',0)->get();
            ClosureTable::query()->truncate();
            while($result->count() > 0){
                $parents = [];
                foreach ($result as $item){
                    $descendant = $item->getKey();
                    $ancestor = isset($item->parent) ? $item->parent : $descendant;
                    $item->closure = new ClosureTable();
                    $item->closure->insertNode($ancestor, $descendant);
                    $parents[] = $descendant;

                }
                $result = SiteContent::query()->whereIn('parent', $parents)->get();
            }

            $this->parameters['end'] = round((microtime(true) - $start), 2);
            $this->parameters['finish'] = 1;

        }



        return true;
    }


}
