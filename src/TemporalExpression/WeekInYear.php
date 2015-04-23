<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class WeekInYear implements TemporalExpressionInterface
{
    /**
     * @var int
     */
    protected $weekIndex;

    /**
     * @param int $weekIndex
     */
    public function __construct($weekIndex)
    {
        $filtered = filter_var($weekIndex, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 53],
        ]);
        if (!$filtered) {
            throw new Exception\InvalidArgumentException(
                'Week must be an integer value between 1 and 53'
            );
        }

        $this->weekIndex = $weekIndex;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return $date->format('W') == $this->weekIndex;
    }
}
