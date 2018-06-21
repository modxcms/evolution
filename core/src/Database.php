<?php namespace EvolutionCMS;

use AgelxNash\Modx\Evo\Database\Database as BaseDatabase;
use Exception;

class Database extends BaseDatabase implements Interfaces\DatabaseInterface
{
    public $config;

    public function query($sql)
    {
        try {
            return parent::query($sql);
        } catch (Exception $exception) {
            $core = evolutionCMS();
            $core->messageQuit($exception->getMessage());
        }
    }

    public function insertFrom(
        $fields,
        $table,
        $fromFields = '*',
        $fromTable = '',
        $where = '',
        $limit = ''
    ) {
        if (is_array($fields)) {
            $onlyKeys = true;
            foreach ($fields as $key => $value) {
                if (!empty($value)) {
                    $onlyKeys = false;
                    break;
                }
            }
            if ($onlyKeys) {
                $fields = array_keys($fields);
            }
        }

        return parent::insertFrom($fields, $table, $fromFields, $fromTable, $where, $limit);
    }
}
