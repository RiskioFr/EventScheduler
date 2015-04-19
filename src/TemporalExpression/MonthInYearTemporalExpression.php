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
        $this->monthIndex = $monthIndex;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return $date->format('m') == $this->monthIndex;
    }
}
