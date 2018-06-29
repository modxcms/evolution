<?php namespace EvolutionCMS\Traits\Models;

use Illuminate\Database\Eloquent\SoftDeletes as BaseSoftDeletes;
use EvolutionCMS\Shit\SoftDeletingScope;

trait SoftDeletes{
    use BaseSoftDeletes {
        bootSoftDeletes as baseBootSoftDeletes;
        restore as baseRestore;
    }

    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new SoftDeletingScope);
    }

    public function restore()
    {
        // If the restoring event does not return false, we will proceed with this
        // restore operation. Otherwise, we bail out so the developer will stop
        // the restore totally. We will clear the deleted timestamp and save.
        if ($this->fireModelEvent('restoring') === false) {
            return false;
        }

        $this->{$this->getDeletedAtColumn()} = 0;

        // Once we have saved the model, we will fire the "restored" event so this
        // developer will do anything they need to after a restore operation is
        // totally finished. Then we will return the result of the save call.
        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('restored', false);

        return $result;
    }
}
