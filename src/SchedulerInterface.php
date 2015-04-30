<?php
namespace Riskio\EventScheduler;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;

interface SchedulerInterface extends Occurrable
{
    /**
     * @param SchedulableEvent $event
     * @param TemporalExpressionInterface $temporalExpression
     */
    public function schedule(SchedulableEvent $event, TemporalExpressionInterface $temporalExpression);

    /**
     * @param SchedulableEvent $event
     */
    public function unschedule(SchedulableEvent $event);

    /**
     * @param  SchedulableEvent $event
     * @return bool
     */
    public function isScheduled(SchedulableEvent $event);

    /**
     * @param  SchedulableEvent $event
     * @param  DateRange $range
     * @return array
     */
    public function dates(SchedulableEvent $event, DateRange $range);

    /**
     * @param  SchedulableEvent $event
     * @param  DateTime $start
     * @param  DateTime|null $end
     * @return ScheduleElementInterface
     */
    public function nextOccurrence(SchedulableEvent $event, DateTime $start, DateTime $end = null);

    /**
     * @param  SchedulableEvent $event
     * @param  DateTime $end
     * @param  DateTime|null $start
     * @return ScheduleElementInterface
     */
    public function previousOccurrence(SchedulableEvent $event, DateTime $end, DateTime $start = null);

    /**
     * @return DateRange
     */
    public function getDateRange();

    /**
     * @param DateRange $range
     */
    public function setDateRange(DateRange $range);
}
