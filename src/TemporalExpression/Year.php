<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

class Year implements TemporalExpressionInterface
{
    /**
     * @var int
     */
    protected $year;

    /**
     * @param int $year
     */
    public function __construct($year)
    {
        if (!is_numeric($year)) {
            throw new Exception\InvalidArgumentException(
                'Year must be numeric value'
            );
        }

        $this->year = $year;
    }

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        return $date->format('Y') == $this->year;
    }
}
