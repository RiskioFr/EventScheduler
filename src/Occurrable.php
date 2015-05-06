<?php
namespace Riskio\EventScheduler;

use DateTimeInterface;

interface Occurrable
{
    /**
     * @param  Event $event
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function isOccurring(Event $event, DateTimeInterface $date);
}
