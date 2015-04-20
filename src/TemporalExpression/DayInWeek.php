<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class DayInWeek implements TemporalExpressionInterface
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
        $filtered = filter_var($dayIndex, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 7],
        ]);
        if (!$filtered) {
            throw new Exception\InvalidArgumentException(
                'Day must be an integer value between 1 and 7'
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
        return $date->format('N') == $this->dayIndex;
    }
}
