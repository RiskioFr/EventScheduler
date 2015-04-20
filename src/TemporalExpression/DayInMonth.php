<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class DayInMonth implements TemporalExpressionInterface
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
        if (!is_numeric($dayIndex) || $dayIndex < 1 || $dayIndex > 31) {
            throw new Exception\InvalidArgumentException(
                'Day must be a numeric value between 1 and 31'
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
        return $date->format('j') == $this->dayIndex;
    }
}
