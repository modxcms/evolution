<?php namespace EvolutionCMS\Shit;

use Illuminate\Database\Eloquent;

class SoftDeletingScope extends Eloquent\SoftDeletingScope
{
    public function apply(Eloquent\Builder $builder, Eloquent\Model $model)
    {
        $builder->where($model->getQualifiedDeletedAtColumn(), '=', 0);
    }

    protected function addWithoutTrashed(Eloquent\Builder $builder)
    {
        $builder->macro('withoutTrashed', function (Eloquent\Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where(
                $model->getQualifiedDeletedAtColumn(), '=', 0
            );

            return $builder;
        });
    }

    protected function addOnlyTrashed(Eloquent\Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Eloquent\Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where(
                $model->getQualifiedDeletedAtColumn(), '!=', 0
            );

            return $builder;
        });
    }
}
