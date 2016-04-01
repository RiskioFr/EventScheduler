<?php
namespace Riskio\EventScheduler;

use Countable;
use Iterator;
use SplObjectStorage;

class SchedulableEventCollection implements Countable, Iterator
{
    /**
     * @var SplObjectStorage
     */
    private $events;

    public function __construct(array $events = [])
    {
        $this->events = new SplObjectStorage;

        foreach ($events as $event) {
            $this->add($event);
        }
    }

    public function add(SchedulableEvent $schedulableEvent)
    {
        $this->events->attach($schedulableEvent);
    }

    public function contains(SchedulableEvent $schedulableEvent) : bool
    {
        return $this->events->contains($schedulableEvent);
    }

    public function remove(SchedulableEvent $schedulableEvent)
    {
        if (!$this->events->contains($schedulableEvent)) {
            throw Exception\NotScheduledEventException::create();
        }

        $this->events->detach($schedulableEvent);
    }

    public function filterByEvent(Event $event) : self
    {
        $filteredEvents = array_filter(
            iterator_to_array($this->events),
            function($scheduledEvent) use ($event) {
                return $event->equals($scheduledEvent->getEvent());
            }
        );

        return new self($filteredEvents);
    }

    public function current() : SchedulableEvent
    {
        return $this->events->current();
    }

    public function next()
    {
        $this->events->next();
    }

    public function key() : int
    {
        return $this->events->key();
    }

    public function valid() : bool
    {
        return $this->events->valid();
    }

    public function rewind()
    {
        $this->events->rewind();
    }

    public function count() : int
    {
        return $this->events->count();
    }
}
