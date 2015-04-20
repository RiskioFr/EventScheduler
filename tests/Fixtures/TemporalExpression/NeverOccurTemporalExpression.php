<?php
namespace Riskio\ScheduleTest\Fixtures\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;

class NeverOccurTemporalExpression implements TemporalExpressionInterface
{
    public function includes(DateTime $date)
    {
        return false;
    }
}
