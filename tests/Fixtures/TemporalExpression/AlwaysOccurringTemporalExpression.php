<?php
namespace Riskio\EventSchedulerTest\Fixtures\TemporalExpression;

use DateTimeInterface;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;

class AlwaysOccurringTemporalExpression implements TemporalExpressionInterface
{
    public function includes(DateTimeInterface $date)
    {
        return true;
    }
}
