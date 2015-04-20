<?php
namespace Riskio\ScheduleTest\Fixtures\ScheduleElement;

use DateTime;
use Riskio\Schedule\ScheduleElementInterface;

class AlwaysOccurElement implements ScheduleElementInterface
{
    public function isOccuring($event, DateTime $date)
    {
        return true;
    }
}
