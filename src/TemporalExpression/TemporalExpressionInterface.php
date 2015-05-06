<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

interface TemporalExpressionInterface
{
    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date);
}
