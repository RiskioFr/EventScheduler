<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

class From implements TemporalExpressionInterface
{
    /**
     * @var DateTimeInterface
     */
    protected $date;

    /**
     * @param DateTimeInterface $date
     */
    public function __construct(DateTimeInterface $date)
    {
        $this->date = $date;
    }

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        return $date >= $this->date;
    }
}
