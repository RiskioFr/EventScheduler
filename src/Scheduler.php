<?php
namespace Riskio\EventScheduler;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Riskio\EventScheduler\DateRange\DateRange;
use Riskio\EventScheduler\DateRange\DateRangeIterator;
use Riskio\EventScheduler\DateRange\DateRangeReverseIterator;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;
use Traversable;

class Scheduler implements SchedulerInterface
{
    /**
     * @var SchedulableEventCollection
     */
    protected $scheduledEvents;

    /**
     * @var DateRange
     */
    protected $dateRange;

    /**
     * @var DateInterval
     */
    protected $interval;

    public function __construct(DateInterval $interval, DateRange $dateRange)
    {
        $this->interval  = $interval;
        $this->dateRange = $dateRange;
        $this->scheduledEvents = new SchedulableEventCollection();
    }

    public static function create(
        DateInterval $interval = null,
        DateRange $dateRange = null
    ) : SchedulerInterface {
        $interval  = $interval ?: new DateInterval('P1D');
        $dateRange = $dateRange ?: DateRange::create(new DateTimeImmutable());

        return new self($interval, $dateRange);
    }

    public function schedule(
        Event $event,
        TemporalExpressionInterface $temporalExpression
    ) : SchedulableEvent {
        $schedulableEvent = new SchedulableEvent($event, $temporalExpression);
        $this->scheduledEvents->add($schedulableEvent);

        return $schedulableEvent;
    }

    public function unschedule(SchedulableEvent $schedulableEvent)
    {
        $this->scheduledEvents->remove($schedulableEvent);
    }

    public function isScheduled(Event $event) : bool
    {
        $scheduledEvents = $this->scheduledEvents->filterByEvent($event);

        return count($scheduledEvents) > 0;
    }

    public function isOccurring(Event $event, DateTimeInterface $date) : bool
    {
        $scheduledEvents = $this->scheduledEvents->filterByEvent($event);
        foreach ($scheduledEvents as $scheduledEvent) {
            if ($scheduledEvent->isOccurring($event, $date)) {
                return true;
            }
        }

        return false;
    }

    public function eventsForDate(DateTimeInterface $date) : Traversable
    {
        foreach ($this->scheduledEvents as $scheduledEvent) {
            $event = $scheduledEvent->event();
            if ($this->isOccurring($event, $date)) {
                yield $event;
            }
        }
    }

    public function dates(Event $event, DateRange $range) : Traversable
    {
        $start = $range->startDate();
        $end   = $range->endDate();

        do {
            try {
                $foundDate = $this->nextOccurrence($event, $start);
            } catch (Exception\NotFoundEventOccurenceException $e) {
                break;
            }

            yield $foundDate;
            $start = $foundDate->add($this->interval);
        } while ($foundDate && $foundDate < $end);
    }

    public function nextOccurrence(
        Event $event,
        DateTimeInterface $start,
        DateTimeInterface $end = null
    ) : DateTimeImmutable {
        $end = $end ?: $this->dateRange()->endDate();

        return $this->findNextOccurrenceInIterator(
            $event,
            $this->createDateRangeIterator($start, $end)
        );
    }

    public function previousOccurrence(
        Event $event,
        DateTimeInterface $end,
        DateTimeInterface $start = null
    ) : DateTimeImmutable {
        $start = $start ?: $this->dateRange()->startDate();

        return $this->findNextOccurrenceInIterator(
            $event,
            $this->createDateRangeReverseIterator($end, $start)
        );
    }

    public function dateRange() : DateRange
    {
        return $this->dateRange;
    }

    public function changeDateRange(DateRange $range)
    {
        $this->dateRange = $range;
    }

    private function createDateRangeIterator(
        DateTimeInterface $start,
        DateTimeInterface $end = null
    ) : DateRangeIterator {
        $dateRange = ($end instanceof DateTimeInterface)
            ? $this->dateRange->extract($start, $end)
            : $this->dateRange->extractFromStartDate($start);

        return new DateRangeIterator($dateRange, $this->interval);
    }

    private function createDateRangeReverseIterator(
        DateTimeInterface $end,
        DateTimeInterface $start = null
    ) : DateRangeReverseIterator {
        $dateRange = ($start instanceof DateTimeInterface)
            ? $this->dateRange->extract($start, $end)
            : $this->dateRange->extractFromEndDate($end);

        return new DateRangeReverseIterator($dateRange, $this->interval);
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
}
