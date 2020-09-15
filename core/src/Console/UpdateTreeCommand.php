<?php namespace EvolutionCMS\Console;

use EvolutionCMS\Models\ClosureTable;
use EvolutionCMS\Models\SiteContent;
use RuntimeException;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * @see: https://github.com/laravel-zero/foundation/blob/5.6/src/Illuminate/Foundation/Console/ViewClearCommand.php
 */
class UpdateTreeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'closuretable:rebuild';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild tree Closure Table';
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(SiteContent::count());

        $bar->start();
        $result = SiteContent::query()->where('parent',0)->get();
        ClosureTable::query()->truncate();
        while($result->count() > 0){
            $parents = [];
            foreach ($result as $item){
                $descendant = $item->getKey();
                $ancestor = isset($item->parent) ? $item->parent : $descendant;
                $item->closure = new ClosureTable();
                $item->closure->insertNode($ancestor, $descendant);
                $bar->advance();
                $parents[] = $descendant;

            }
            $result = SiteContent::query()->whereIn('parent', $parents)->get();
        }

        $bar->finish();
    }
}
