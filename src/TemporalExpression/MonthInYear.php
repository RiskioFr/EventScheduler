<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class MonthInYear implements TemporalExpressionInterface
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
        $filtered = filter_var($monthIndex, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 12],
        ]);
        if (!$filtered) {
            throw new Exception\InvalidArgumentException(
                'Month must be an integer value be between 1 and 12'
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
