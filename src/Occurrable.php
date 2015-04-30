<?php
namespace Riskio\Schedule;

use DateTime;

interface Occurrable
{
    /**
     * @param  SchedulableEvent $event
     * @param  DateTime $date
     * @return bool
     */
    public function isOccurring(SchedulableEvent $event, DateTime $date);
}
