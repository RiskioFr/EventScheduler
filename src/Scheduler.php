<?php
namespace Riskio\EventScheduler;

use DateInterval;
use DateTime;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;
use Traversable;

class Scheduler implements SchedulerInterface
{
    /**
     * @var SchedulerEvent[]
     */
    protected $scheduledEvents = [];

    /**
     * @var DateRange
     */
    protected $dateRange;

    /**
     * {@inheritdoc}
     */
    public function schedule(
        SchedulableEvent $event,
        TemporalExpressionInterface $temporalExpression
    ) {
        if ($this->isScheduled($event)) {
            throw Exception\AlreadyScheduledEventException::create();
        }

        $this->scheduledEvents[] = new SchedulerEvent($event, $temporalExpression);
    }

    /**
     * {@inheritdoc}
     */
    public function unschedule(
        SchedulableEvent $event,
        TemporalExpressionInterface $temporalExpression = null
    ) {
        $scheduledEvents = $this->getScheduledEvents($event, $temporalExpression);

        foreach ($scheduledEvents as $scheduledEvent) {
            $key = array_search($scheduledEvent, $this->scheduledEvents);
            unset($this->scheduledEvents[$key]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isScheduled(SchedulableEvent $event)
    {
        try {
            $this->getScheduledEvents($event);
        } catch (Exception\NotScheduledEventException $e) {
            return false;
        }

        return true;
    }

    private function getScheduledEvents(SchedulableEvent $event, TemporalExpressionInterface $temporalExpression = null)
    {
        $scheduledEvents = [];
        foreach ($this->scheduledEvents as $scheduledEvent) {
            if (!$event->compare($scheduledEvent->getEvent())) {
                continue;
            }

            if (
                null !== $temporalExpression
                && $temporalExpression !== $scheduledEvent->getTemporalExpression()
            ) {
                continue;
            }

            $scheduledEvents[] = $scheduledEvent;
        }

        if (!empty($scheduledEvents)) {
            return $scheduledEvents;
        }

        throw Exception\NotScheduledEventException::create();
    }

    /**
     * {@inheritdoc}
     */
    public function isOccurring(SchedulableEvent $event, DateTime $date)
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
    public function eventsForDate(DateTime $date)
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
    public function dates(SchedulableEvent $event, DateRange $range)
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
    public function nextOccurrence(SchedulableEvent $event, DateTime $start, DateTime $end = null)
    {
        $end      = $end ?: $this->getDateRange()->getEndDate();
        $iterator = (new DateRange($start, $end))->getIterator();

        return $this->findNextOccurrenceInIterator($event, $iterator);
    }

    /**
     * {@inheritdoc}
     */
    public function previousOccurrence(SchedulableEvent $event, DateTime $end, DateTime $start = null)
    {
        $start    = $start ?: $this->getDateRange()->getStartDate();
        $iterator = (new DateRange($start, $end))->getReverseIterator();

        return $this->findNextOccurrenceInIterator($event, $iterator);
    }

    private function findNextOccurrenceInIterator(SchedulableEvent $event, Traversable $dates)
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
            $start    = (new DateTime())->sub($interval);
            $end      = (new DateTime())->add($interval);

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
