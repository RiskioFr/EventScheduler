<?php
namespace Riskio\EventScheduler;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Riskio\EventScheduler\DateRange\DateRange;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;
use Traversable;

interface SchedulerInterface extends Occurrable
{
    public static function create(
        DateInterval $interval = null,
        DateRange $dateRange = null
    ) : self;

    public function schedule(
        Event $event,
        TemporalExpressionInterface $temporalExpression
    ) : SchedulableEvent;

    public function unschedule(SchedulableEvent $schedulableEvent);

    public function isScheduled(Event $event) : bool;

    public function eventsForDate(DateTimeInterface $date) : Traversable;

    public function dates(Event $event, DateRange $range) : Traversable;

    public function nextOccurrence(
        Event $event,
        DateTimeInterface $start,
        DateTimeInterface $end = null
    ) : DateTimeImmutable;

    public function previousOccurrence(
        Event $event,
        DateTimeInterface $end,
        DateTimeInterface $start = null
    ) : DateTimeImmutable;

    public function dateRange() : DateRange;

    public function changeDateRange(DateRange $range);
}
