<?php
namespace Riskio\EventSchedulerTest\Fixtures\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;

class NeverOccurringTemporalExpression implements TemporalExpressionInterface
{
    public function includes(DateTime $date)
    {
        return false;
    }
}
