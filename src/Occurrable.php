<?php
namespace Riskio\EventScheduler;

use DateTimeInterface;

interface Occurrable
{
    public function isOccurring(Event $event, DateTimeInterface $date) : bool;
}
