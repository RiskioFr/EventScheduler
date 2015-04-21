<?php
namespace Riskio\ScheduleTest\Fixtures\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;

class AlwaysOccurringTemporalExpression implements TemporalExpressionInterface
{
    public function includes(DateTime $date)
    {
        return true;
    }
}
