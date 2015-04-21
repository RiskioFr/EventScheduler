<?php
namespace Riskio\ScheduleTest\Fixtures\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;

class NeverOccurringTemporalExpression implements TemporalExpressionInterface
{
    public function includes(DateTime $date)
    {
        return false;
    }
}
