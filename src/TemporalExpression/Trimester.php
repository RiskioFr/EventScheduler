<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class Trimester implements TemporalExpressionInterface
{
    const FIRST  = 1;
    const SECOND = 2;
    const THIRD  = 3;
    const FOURTH = 4;

    /**
     * @var int
     */
    protected $trimester;

    /**
     * @param int $trimester
     */
    public function __construct($trimester)
    {
        $filtered = filter_var($trimester, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 4],
        ]);
        if (!$filtered) {
            throw new Exception\InvalidArgumentException(
                'Trimester must be an integer value among 1, 2, 3 or 4 according to'
                . ' first, second, third and fourth trimesters of the year'
            );
        }

        $this->trimester = $trimester;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return ceil($date->format('n') / 3) == $this->trimester;
    }
}
