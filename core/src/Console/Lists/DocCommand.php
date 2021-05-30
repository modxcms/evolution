<?php namespace EvolutionCMS\Console\Lists;

use EvolutionCMS\Models\SiteContent;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DocCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doc:list ' .
    '{--parent-id=* : Parents ID}' .
    '{--P|published : Only published}' .
    '{--D|deleted : With deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Documents from the site_content table';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        /** @var Builder $query */
        $query = SiteContent::orderBy('id', 'ASC');

        if ($this->option('parent-id') !== []) {
            $query->whereIn('parent', $this->option('parent-id'));
        }

        if ($this->option('published')) {
            $query->where('published', '=', 1);
        }

        if ($this->option('deleted')) {
            $query->withTrashed();
        }

        $query->chunk(1000, function (Collection $collection) {
            $collection->map(function ($item) {
                $this->process($item);
            });
        });
    }

    protected function process(SiteContent $item)
    {
        $this->getOutput()->write(
            '[ ' . $item->getKey() . ' ] <info>' . $item->pagetitle . '</info>'
        );

        if ($item->tpl->exists) {
            $this->getOutput()->write(
                ' / <comment>' . $item->tpl->name . '</comment> (' . $item->template . ')'
            );
        }

        $this->line('');

        if ($item->tv->count() !== 0) {
            $this->table(['TV name', 'TV value'], $item->tv);
        }

        $this->line('');
    }
}
