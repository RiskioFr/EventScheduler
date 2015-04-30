<?php
namespace Riskio\EventScheduler\TemporalExpression;

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
        $filtered = filter_var($semester, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 2],
        ]);
        if (false === $filtered) {
            throw new Exception\InvalidArgumentException(
                'Semester must be an integer value among 1 or 2 according to'
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
