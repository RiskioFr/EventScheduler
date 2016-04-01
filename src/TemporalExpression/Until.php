<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

class Until implements TemporalExpressionInterface
{
    /**
     * @var DateTimeInterface
     */
    protected $date;

    public function __construct(DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public function includes(DateTimeInterface $date) : bool
    {
        return $date <= $this->date;
    }
}
