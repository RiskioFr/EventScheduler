<?php
namespace Riskio\EventScheduler;

use DateInterval;
use DateTime;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;
use Traversable;

class Scheduler implements SchedulerInterface
{
    /**
     * @var array
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
            throw new Exception\AlreadyScheduledEventException('Provided event already scheduled');
        }

        $this->scheduledEvents[] = [$event, $temporalExpression];
    }

    /**
     * {@inheritdoc}
     */
    public function unschedule(SchedulableEvent $event)
    {
        $scheduledEvent = $this->getScheduledEvent($event);
        $key = array_search($scheduledEvent, $this->scheduledEvents);

        unset($this->scheduledEvents[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function isScheduled(SchedulableEvent $event)
    {
        try {
            $this->getScheduledEvent($event);
        } catch (Exception\NotScheduledEventException $e) {
            return false;
        }

        return true;
    }

    private function getScheduledEvent(SchedulableEvent $event)
    {
        foreach ($this->scheduledEvents as $item) {
            if ($event->compare($item[0])) {
                return $item;
            }
        }

        throw new Exception\NotScheduledEventException('Provided event is not scheduled');
    }

    /**
     * {@inheritdoc}
     */
    public function isOccurring(SchedulableEvent $event, DateTime $date)
    {
        $scheduleEvent = $this->getScheduledEvent($event);

        list(, $temporalExpression) = $scheduleEvent;

        return $temporalExpression->includes($date);
    }

    /**
     * {@inheritdoc}
     */
    public function eventsForDate(DateTime $date)
    {
        foreach ($this->scheduledEvents as $scheduledEvent) {
            list($event,) = $scheduledEvent;

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
