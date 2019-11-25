<?php namespace EvolutionCMS\Tracy\Panels\Event;

use Tracy\Debugger;
use EvolutionCMS\Tracy\Panels\AbstractPanel;

class Panel extends AbstractPanel
{
    /**
     * $counter.
     *
     * @var int
     */
    protected $counter = 0;
    /**
     * $totalTime.
     *
     * @var float
     */
    protected $totalTime = 0.0;
    /**
     * $events.
     *
     * @var array
     */
    protected $events = [];
    /**
     * getAttributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return [
            'counter' => $this->counter,
            'totalTime' => $this->totalTime,
            'events' => $this->events,
        ];
    }
    /**
     * subscribe.
     */
    protected function subscribe()
    {
        $id = \get_class($this);
        Debugger::timer($id);
        $events = $this->evolution['events'];

        $events->listen('evolution.*', function ($key, $payload) use ($id) {
            $execTime = Debugger::timer($id);
            $editorLink = static::editorLink(static::findSource());
            $this->totalTime += $execTime;
            $this->events[] = compact('execTime', 'key', 'payload', 'editorLink');
        });
    }
}
