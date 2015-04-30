<?php
namespace Riskio\ScheduleTest\Fixtures\ScheduleElement;

use DateTime;
use Riskio\Schedule\SchedulableEvent;
use Riskio\Schedule\ScheduleElementInterface;

class AlwaysOccurringElement implements ScheduleElementInterface
{
    public function isOccurring(SchedulableEvent $event, DateTime $date)
    {
        return true;
    }
}
