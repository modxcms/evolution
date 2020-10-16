<?php namespace EvolutionCMS\Interfaces;

interface ServiceInterface
{
    public function get(int $id);

    public function create(array $data);

    public function delete(int $id);

    public function edit(int $id, array $data);

}
