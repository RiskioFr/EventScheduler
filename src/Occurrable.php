<?php
namespace Riskio\EventScheduler;

use DateTimeInterface;

interface Occurrable
{
    /**
     * @param  SchedulableEvent $event
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function isOccurring(SchedulableEvent $event, DateTimeInterface $date);
}
