<?php
namespace Riskio\ScheduleTest\Fixtures;

use Riskio\Schedule\Comparable;
use Riskio\Schedule\SchedulableEvent;

class Event implements SchedulableEvent
{
    public function compare(Comparable $compare)
    {
        return $this === $compare;
    }
}
