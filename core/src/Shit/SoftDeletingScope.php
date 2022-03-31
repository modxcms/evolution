<?php namespace EvolutionCMS\Shit;

use Illuminate\Database\Eloquent;

class SoftDeletingScope extends Eloquent\SoftDeletingScope
{
    protected $extensions = ['Restore', 'WithTrashed', 'WithoutTrashed', 'OnlyTrashed', 'WithoutSoftDeletingScope'];

    public function apply(Eloquent\Builder $builder, Eloquent\Model $model)
    {
        $builder->where('deleted', '=', 0);
    }

    protected function addWithoutSoftDeletingScope(Eloquent\Builder $builder)
    {
        $builder->macro('withoutSoftDeletingScope', function (Eloquent\Builder $builder) {
            $builder->withoutGlobalScope($this);

            return $builder;
        });
    }

    protected function addWithoutTrashed(Eloquent\Builder $builder)
    {
        $builder->macro('withoutTrashed', function (Eloquent\Builder $builder) {
            $builder->withoutGlobalScope($this)->where(
                'deleted', '=', 0
            );

            return $builder;
        });
    }

    protected function addOnlyTrashed(Eloquent\Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Eloquent\Builder $builder) {
            $builder->withoutGlobalScope($this)->where(
                'deleted', '!=', 0
            );

            return $builder;
        });
    }
}
