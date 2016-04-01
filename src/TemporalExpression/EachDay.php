<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

class EachDay implements TemporalExpressionInterface
{
    public function includes(DateTimeInterface $date) : bool
    {
        return true;
    }
}
