<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class TrimesterTemporalExpression implements TemporalExpressionInterface
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
        if (!is_numeric($trimester) || $trimester < 1 || $trimester > 4) {
            throw new Exception\InvalidArgumentException(
                'Trimester must be a numeric value among 1, 2, 3 or 4 according to'
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
