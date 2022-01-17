<?php namespace EvolutionCMS\Tracy;

use Tracy\Debugger as BaseDebugger;

class Debugger extends BaseDebugger
{
    public static function enable($mode = null, string $logDirectory = null, $email = null): void
    {
        parent::enable($mode, $logDirectory, $email);

        set_error_handler([__CLASS__, 'errorHandler']);
    }

    public static function errorHandler(int $severity, string $message, string $file, int $line, ?array $context = []): bool
    {
        if (!empty(evolutionCMS()->currentSnippet)) {
            $file = 'Snippet: ' . evolutionCMS()->currentSnippet;
        }

        if (!empty(evolutionCMS()->event->activePlugin)) {
            $file = 'Plugin ' . evolutionCMS()->event->name . '[' . evolutionCMS()->event->activePlugin.']';
        }

        return parent::errorHandler($severity, $message, $file, $line, $context);
    }
}
