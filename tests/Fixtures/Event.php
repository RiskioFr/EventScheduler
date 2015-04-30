<?php
namespace Riskio\EventSchedulerTest\Fixtures;

use Riskio\EventScheduler\Comparable;
use Riskio\EventScheduler\SchedulableEvent;

class Event implements SchedulableEvent
{
    public function compare(Comparable $compare)
    {
        return $this === $compare;
    }
}
