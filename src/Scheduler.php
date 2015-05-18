<?php
namespace Riskio\EventScheduler;

use DateInterval;
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

    /**
     * {@inheritdoc}
     */
    public function schedule(Event $event, TemporalExpressionInterface $temporalExpression)
    {
        $schedulableEvent = new SchedulableEvent($event, $temporalExpression);
        $this->scheduledEvents->attach($schedulableEvent);

        return $schedulableEvent;
    }

    /**
     * {@inheritdoc}
     */
    public function unschedule(SchedulableEvent $schedulableEvent)
    {
        if (!$this->scheduledEvents->contains($schedulableEvent)) {
            throw Exception\NotScheduledEventException::create();
        }

        $this->scheduledEvents->detach($schedulableEvent);
    }

    /**
     * {@inheritdoc}
     */
    public function isScheduled(Event $event)
    {
        $scheduledEvents = $this->getScheduledEvents($event);

        return !empty($scheduledEvents);
    }

    private function getScheduledEvents(Event $event)
    {
        $scheduledEvents = [];
        foreach ($this->scheduledEvents as $scheduledEvent) {
            if (!$event->equals($scheduledEvent->getEvent())) {
                continue;
            }

            $scheduledEvents[] = $scheduledEvent;
        }

        return $scheduledEvents;
    }

    /**
     * {@inheritdoc}
     */
    public function isOccurring(Event $event, DateTimeInterface $date)
    {
        $scheduleEvents = $this->getScheduledEvents($event);
        foreach ($scheduleEvents as $scheduleEvent) {
            $isOccurring = $scheduleEvent->isOccurring($event, $date);
            if ($isOccurring) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function eventsForDate(DateTimeInterface $date)
    {
        foreach ($this->scheduledEvents as $scheduledEvent) {
            $event = $scheduledEvent->getEvent();
            if ($this->isOccurring($event, $date)) {
                yield $event;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dates(Event $event, DateRange $range)
    {
        $end = $range->getEndDate();

        for (
            $start = $range->getStartDate();
            $date  = $this->nextOccurrence($event, $start, $end);
            $start = $date->add($range->getInterval())
        ) {
            yield $date;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function nextOccurrence(Event $event, DateTimeInterface $start, DateTimeInterface $end = null)
    {
        $end      = $end ?: $this->getDateRange()->getEndDate();
        $iterator = (new DateRange($start, $end))->getIterator();

        return $this->findNextOccurrenceInIterator($event, $iterator);
    }

    /**
     * {@inheritdoc}
     */
    public function previousOccurrence(Event $event, DateTimeInterface $end, DateTimeInterface $start = null)
    {
        $start    = $start ?: $this->getDateRange()->getStartDate();
        $iterator = (new DateRange($start, $end))->getReverseIterator();

        return $this->findNextOccurrenceInIterator($event, $iterator);
    }

    private function findNextOccurrenceInIterator(Event $event, Traversable $dates)
    {
        foreach ($dates as $date) {
            if ($this->isOccurring($event, $date)) {
                return $date;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDateRange()
    {
        if (!$this->dateRange) {
            $interval = new DateInterval('P1Y');
            $start    = (new DateTimeImmutable())->sub($interval);
            $end      = (new DateTimeImmutable())->add($interval);

            $this->setDateRange(new DateRange($start, $end));
        }

        return $this->dateRange;
    }

    /**
     * {@inheritdoc}
     */
    public function setDateRange(DateRange $range)
    {
        $this->dateRange = $range;
    }
}
