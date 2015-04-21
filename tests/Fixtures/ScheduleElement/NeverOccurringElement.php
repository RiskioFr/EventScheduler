<?php
namespace Riskio\ScheduleTest\Fixtures\ScheduleElement;

use DateTime;
use Riskio\Schedule\ScheduleElementInterface;

class NeverOccurringElement implements ScheduleElementInterface
{
    public function isOccurring($event, DateTime $date)
    {
        return false;
    }
}
