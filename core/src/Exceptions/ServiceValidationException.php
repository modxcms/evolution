<?php

namespace EvolutionCMS\Exceptions;

use Exception;

class ServiceValidationException extends Exception
{
    private $validationErrors = [];

    public function setValidationErrors(array $errors = []): void
    {
        $this->validationErrors = $errors;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
