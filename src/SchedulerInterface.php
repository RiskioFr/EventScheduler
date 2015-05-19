<?php
namespace Riskio\EventScheduler;

use DateTimeInterface;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;

interface SchedulerInterface extends Occurrable
{
    /**
     * @param  Event $event
     * @param  TemporalExpressionInterface $temporalExpression
     * @return SchedulableEvent
     */
    public function schedule(Event $event, TemporalExpressionInterface $temporalExpression);

    /**
     * @param SchedulableEvent $schedulableEvent
     */
    public function unschedule(SchedulableEvent $schedulableEvent);

    /**
     * @param  Event $event
     * @return bool
     */
    public function isScheduled(Event $event);

    /**
     * @param  DateTimeInterface $date
     * @return Traversable
     */
    public function eventsForDate(DateTimeInterface $date);

    /**
     * @param  Event $event
     * @param  DateRange $range
     * @return Traversable
     */
    public function dates(Event $event, DateRange $range);

    /**
     * @param  Event $event
     * @param  DateTimeInterface $start
     * @param  DateTimeInterface|null $end
     * @return ScheduleElementInterface
     */
    public function nextOccurrence(Event $event, DateTimeInterface $start, DateTimeInterface $end = null);

    /**
     * @param  Event $event
     * @param  DateTimeInterface $end
     * @param  DateTimeInterface|null $start
     * @return ScheduleElementInterface
     */
    public function previousOccurrence(Event $event, DateTimeInterface $end, DateTimeInterface $start = null);

    /**
     * @return DateRange
     */
    public function getDateRange();

    /**
     * @param DateRange $range
     */
    public function setDateRange(DateRange $range);
}
