<?php
namespace Riskio\ScheduleModule\TemporalExpression;

use DateTime;

class MonthInYearTemporalExpression implements TemporalExpressionInterface
{
    /**
     * @var int
     */
    protected $monthIndex;

    /**
     * @param int $monthIndex
     */
    public function __construct($monthIndex)
    {
        if (!is_numeric($monthIndex) || $monthIndex < 1 || $monthIndex > 12) {
            throw new Exception\InvalidArgumentException(
                'Month must a numeric value be between 1 and 12'
            );
        }

        $this->monthIndex = $monthIndex;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return $date->format('n') == $this->monthIndex;
    }
}
