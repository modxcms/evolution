<?php namespace EvolutionCMS\Console\Lists;

use EvolutionCMS\Models\SiteTemplate;
use EvolutionCMS\Models\SiteTmplvar;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TvCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tv:list';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TV lists';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        /** @var Builder $query */
        $query = SiteTmplvar::orderBy('id', 'ASC');

        $query->chunk(1000, function (Collection $collection) {
            $collection->map(function ($item) {
                $this->process($item);
            });
        });
    }

    protected function process(SiteTmplvar $item)
    {
        $this->getOutput()->writeLn(
            '[ ' . $item->getKey() . ' ] <info>' . $item->name . '</info>'
        );


        $this->getOutput()->writeLn(
            ' / <comment>' . $item->type . '</comment> (' . $item->default_text . ')'
        );


        if ($item->templates->count() > 0) {
            $this->table(
                ['id', 'name'],
                $item->templates->map(
                    function (SiteTemplate $item) {
                        return $item->only(['id', 'name']);
                    }
                )
            );
        }

        $this->line('');
    }
}
