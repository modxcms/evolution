<?php namespace EvolutionCMS\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ServiceInterface
{
    /**
     * @param array $data
     * @param bool $events
     * @param bool $cache
     */
    public function __construct(array $data, bool $events, bool $cache);

    /**
     * @return Model
     */
    public function process();

    /**
     * @return array
     */
    public function getValidationRules(): array;

    /**
     * @return array
     */
    public function getValidationMessages(): array;

    /**
     * @return bool
     */
    public function checkRules(): bool;

    /**
     * @return bool
     */
    public function validate(): bool;
}
