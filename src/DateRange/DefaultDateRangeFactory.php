<?php
namespace Riskio\EventScheduler\DateRange;

use DateInterval;
use DateTimeImmutable;

class DefaultDateRangeFactory
{
    public static function create(DateTimeImmutable $date, DateInterval $interval = null) : DateRange
    {
        $interval = $interval ?: new DateInterval('P1Y');
        $start    = $date->sub($interval);
        $end      = $date->add($interval);

        return new DateRange($start, $end);
    }
}
