<?php
namespace Riskio\ScheduleModule\TemporalExpression;

use DateTime;

class DayInMonthTemporalExpression implements TemporalExpressionInterface
{
    /**
     * @var int
     */
    protected $dayIndex;

    /**
     * @param int $dayIndex
     */
    public function __construct($dayIndex)
    {
        $this->dayIndex = $dayIndex;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return $date->format('d') == $this->dayIndex;
    }
}
