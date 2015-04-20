<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

interface TemporalExpressionInterface
{
    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date);
}
