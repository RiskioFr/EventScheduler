<?php
namespace Riskio\Schedule;

use DateTime;

interface ScheduleElementInterface
{
    /**
     * @param  Comparable $event
     * @param  DateTime $date
     * @return bool
     */
    public function isOccurring(Comparable $event, DateTime $date);
}
