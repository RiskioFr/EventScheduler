<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class DayInWeekTemporalExpression implements TemporalExpressionInterface
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
        if (!is_numeric($dayIndex) || $dayIndex < 0 || $dayIndex > 6) {
            throw new Exception\InvalidArgumentException(
                'Day must be a numeric value between 0 and 6'
            );
        }

        $this->dayIndex = $dayIndex;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return $date->format('w') == $this->dayIndex;
    }
}
