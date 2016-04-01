<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

class LeapYear implements TemporalExpressionInterface
{
    public function includes(DateTimeInterface $date) : bool
    {
        return $date->format('L') == 1;
    }
}
