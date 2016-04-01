<?php
namespace Riskio\EventScheduler;

use DateTimeImmutable;
use DateTimeInterface;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;
use SplObjectStorage;
use Traversable;

class Scheduler implements SchedulerInterface
{
    /**
     * @var SplObjectStorage
     */
    protected $scheduledEvents;

    /**
     * @var DateRange
     */
    protected $dateRange;

    public function __construct()
    {
        $this->scheduledEvents = new SplObjectStorage();
    }

    public function schedule(Event $event, TemporalExpressionInterface $temporalExpression) : SchedulableEvent
    {
        $schedulableEvent = new SchedulableEvent($event, $temporalExpression);
        $this->scheduledEvents->attach($schedulableEvent);

        return $schedulableEvent;
    }

    public function unschedule(SchedulableEvent $schedulableEvent)
    {
        if (!$this->scheduledEvents->contains($schedulableEvent)) {
            throw Exception\NotScheduledEventException::create();
        }

        $this->scheduledEvents->detach($schedulableEvent);
    }

    public function isScheduled(Event $event) : bool
    {
        $scheduledEvents = $this->getScheduledEvents($event);

        return !empty($scheduledEvents);
    }

    private function getScheduledEvents(Event $event) : array
    {
        return array_filter(
            iterator_to_array($this->scheduledEvents),
            function($scheduledEvent) use ($event) {
                return $event->equals($scheduledEvent->getEvent());
            }
        );
    }

    public function isOccurring(Event $event, DateTimeInterface $date) : bool
    {
        $scheduleEvents = $this->getScheduledEvents($event);
        foreach ($scheduleEvents as $scheduleEvent) {
            if ($scheduleEvent->isOccurring($event, $date)) {
                return true;
            }
        }

        return false;
    }

    public function eventsForDate(DateTimeInterface $date) : Traversable
    {
        foreach ($this->scheduledEvents as $scheduledEvent) {
            $event = $scheduledEvent->getEvent();
            if ($this->isOccurring($event, $date)) {
                yield $event;
            }
        }
    }

    public function dates(Event $event, DateRange $range) : Traversable
    {
        $end = $range->getEndDate();

        try {
            for (
                $start = $range->getStartDate();
                $date = $this->nextOccurrence($event, $start, $end);
                $start = $date->add($range->getInterval())
            ) {
                yield $date;
            }
        } catch (Exception\NotFoundEventOccurenceException $e) {}
    }

    public function nextOccurrence(Event $event, DateTimeInterface $start, DateTimeInterface $end = null) : DateTimeImmutable
    {
        $end      = $end ?: $this->getDateRange()->getEndDate();
        $iterator = (new DateRange($start, $end))->getIterator();

        return $this->findNextOccurrenceInIterator($event, $iterator);
    }

    public function previousOccurrence(Event $event, DateTimeInterface $end, DateTimeInterface $start = null) : DateTimeImmutable
    {
        $start    = $start ?: $this->getDateRange()->getStartDate();
        $iterator = (new DateRange($start, $end))->getReverseIterator();

        return $this->findNextOccurrenceInIterator($event, $iterator);
    }

    private function findNextOccurrenceInIterator(Event $event, Traversable $dates) : DateTimeImmutable
    {
        foreach ($dates as $date) {
            if ($this->isOccurring($event, $date)) {
                return $date;
            }
        }

        throw Exception\NotFoundEventOccurenceException::create();
    }

    public function getDateRange() : DateRange
    {
        if (!$this->dateRange) {
            $this->setDateRange(DateRange::create(new DateTimeImmutable()));
        }

        return $this->dateRange;
    }

    public function setDateRange(DateRange $range)
    {
        $this->dateRange = $range;
    }
}
