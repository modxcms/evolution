<?php namespace EvolutionCMS\Interfaces;

interface ServiceInterface
{
    public function process();

    public function getValidationRules(): array;

    public function getValidationMessages(): array;

    public function checkRules(): bool;

    public function validation(): void;
}
