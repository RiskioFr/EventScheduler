<?php
namespace Riskio\ScheduleTest\Fixtures\ScheduleElement;

use DateTime;
use Riskio\Schedule\ScheduleElementInterface;

class NeverOccurElement implements ScheduleElementInterface
{
    public function isOccuring($event, DateTime $date)
    {
        return false;
    }
}
