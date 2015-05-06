<?php
namespace Riskio\EventScheduler;

use DateTimeInterface;

interface SchedulerInterface extends Occurrable
{
    /**
     * @param SchedulableEvent $schedulableEvent
     */
    public function schedule(SchedulableEvent $schedulableEvent);

    /**
     * @param SchedulableEvent $schedulableEvent
     */
    public function unschedule(SchedulableEvent $schedulableEvent);

    /**
     * @param  SchedulableEvent $schedulableEvent
     * @return bool
     */
    public function isScheduled(SchedulableEvent $schedulableEvent);

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
