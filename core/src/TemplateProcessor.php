<?php namespace EvolutionCMS;

use EvolutionCMS\Interfaces\CoreInterface;
use EvolutionCMS\Models\SiteContent;

class TemplateProcessor
{
    /**
     * @var Interfaces\CoreInterface
     */
    protected $core;


    public function __construct(Interfaces\CoreInterface $core)
    {
        $this->core = $core;
    }


}
