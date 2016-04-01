<?php
namespace Riskio\EventSchedulerTest\Fixtures\TemporalExpression;

use DateTimeInterface;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;

class NeverOccurringTemporalExpression implements TemporalExpressionInterface
{
    public function includes(DateTimeInterface $date) : bool
    {
        return false;
    }
}
