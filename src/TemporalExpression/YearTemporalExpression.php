<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class YearTemporalExpression implements TemporalExpressionInterface
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
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return $date->format('Y') == $this->year;
    }
}
