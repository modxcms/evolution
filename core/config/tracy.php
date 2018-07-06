<?php
return [
    'active' => false,
    'panels' => [
        EvolutionCMS\Tracy\Panels\Database\Panel::class,
        EvolutionCMS\Tracy\Panels\Routing\Panel::class,
        EvolutionCMS\Tracy\Panels\Request\Panel::class,
        EvolutionCMS\Tracy\Panels\Session\Panel::class,
        EvolutionCMS\Tracy\Panels\Event\Panel::class,
        EvolutionCMS\Tracy\Panels\View\Panel::class,
        EvolutionCMS\Tracy\Panels\Auth\Panel::class
    ]
];
