<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

class LeapYear implements TemporalExpressionInterface
{
    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        return $date->format('L') == 1;
    }
}
