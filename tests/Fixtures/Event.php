<?php
namespace Riskio\EventSchedulerTest\Fixtures;

use Riskio\EventScheduler\Comparable;
use Riskio\EventScheduler\Event as BaseEvent;

class Event implements BaseEvent
{
    public function equals(Comparable $compare) : bool
    {
        return $this === $compare;
    }
}
