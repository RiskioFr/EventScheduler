<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

interface TemporalExpressionInterface
{
    public function includes(DateTimeInterface $date) : bool;
}
