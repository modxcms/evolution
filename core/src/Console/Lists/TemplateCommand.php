<?php namespace EvolutionCMS\Console\Lists;

use EvolutionCMS\Models\SiteTemplate;
use EvolutionCMS\Models\SiteTmplvar;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TemplateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tpl:list';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Templates from the site_template table';
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        /** @var Builder $query */
        $query = SiteTemplate::orderBy('id', 'ASC');

        $query->chunk(1000, function (Collection $collection) {
            $collection->map(function ($item) {
                $this->process($item);
            });
        });
    }

    protected function process(SiteTemplate $item)
    {
        $this->getOutput()->write(
            '[ ' . $item->getKey() . ' ] <info>' . $item->name . '</info>'
        );

        if ($item->categoryId() !== null) {
            $this->getOutput()->write(
                ' / <comment>' . $item->categoryName('-') . '</comment> (' . $item->categoryId() . ')'
            );
        }

        $this->line('');

        if ($item->tvs->count() > 0) {
            $this->table(
                ['id', 'name', 'default'],
                $item->tvs->map(
                    function (SiteTmplvar $item) {
                        return $item->only(['id', 'name', 'default_text']);
                    }
                )
            );
        }


        $this->line('');
    }
}
