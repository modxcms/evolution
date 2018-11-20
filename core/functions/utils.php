<?php
if (! function_exists('var_debug')) {
    /**
     * Dumps information about a variable in Tracy Debug Bar.
     * @tracySkipLocation
     * @param  mixed $var
     * @param  string $title
     * @param  array $options
     * @return mixed  variable itself
     */
    function var_debug($var, $title = null, array $options = null)
    {
        return EvolutionCMS\Tracy\Debugger::barDump($var, $title, $options);
    }
}

if (! function_exists('evo_parser')) {
    function evo_parser($content)
    {
        $core = evolutionCMS();
        $core->minParserPasses = 2;
        $core->maxParserPasses = 10;

        $out = \EvolutionCMS\Parser::getInstance($core)->parseDocumentSource($content, $core);

        $core->minParserPasses = -1;
        $core->maxParserPasses = -1;

        return $out;
    }
}
