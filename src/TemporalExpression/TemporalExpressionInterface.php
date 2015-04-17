<?php
namespace Riskio\ScheduleModule\TemporalExpression;

use DateTime;

interface TemporalExpressionInterface
{
    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date);
}
