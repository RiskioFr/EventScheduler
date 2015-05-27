<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

class EachDay implements TemporalExpressionInterface
{
    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        return true;
    }
}
