<?php
namespace Riskio\EventScheduler;

use DateTimeInterface;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;

interface SchedulerInterface extends Occurrable
{
    /**
     * @param SchedulableEvent $event
     * @param TemporalExpressionInterface $temporalExpression
     */
    public function schedule(
        SchedulableEvent $event,
        TemporalExpressionInterface $temporalExpression
    );

    /**
     * @param SchedulableEvent $event
     * @param TemporalExpressionInterface|null $temporalExpression
     */
    public function unschedule(
        SchedulableEvent $event,
        TemporalExpressionInterface $temporalExpression = null
    );

    /**
     * @param  SchedulableEvent $event
     * @return bool
     */
    public function isScheduled(SchedulableEvent $event);

    /**
     * @param  DateTimeInterface $date
     * @return Traversable
     */
    public function eventsForDate(DateTimeInterface $date);

    /**
     * @param  SchedulableEvent $event
     * @param  DateRange $range
     * @return Traversable
     */
    public function dates(SchedulableEvent $event, DateRange $range);

    /**
     * @param  SchedulableEvent $event
     * @param  DateTimeInterface $start
     * @param  DateTimeInterface|null $end
     * @return ScheduleElementInterface
     */
    public function nextOccurrence(SchedulableEvent $event, DateTimeInterface $start, DateTimeInterface $end = null);

    /**
     * @param  SchedulableEvent $event
     * @param  DateTimeInterface $end
     * @param  DateTimeInterface|null $start
     * @return ScheduleElementInterface
     */
    public function previousOccurrence(SchedulableEvent $event, DateTimeInterface $end, DateTimeInterface $start = null);

    /**
     * @return DateRange
     */
    public function getDateRange();

    /**
     * @param DateRange $range
     */
    public function setDateRange(DateRange $range);
}
