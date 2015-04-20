<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class Semester implements TemporalExpressionInterface
{
    const FIRST  = 1;
    const SECOND = 2;

    /**
     * @var int
     */
    protected $semester;

    /**
     * @param int $semester
     */
    public function __construct($semester)
    {
        if (!is_numeric($semester) || $semester < 1 || $semester > 2) {
            throw new Exception\InvalidArgumentException(
                'Semester must be a numeric value among 1 or 2 according to'
                . ' first and second semesters of the year'
            );
        }

        $this->semester = $semester;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return ceil($date->format('n') / 6) == $this->semester;
    }
}
