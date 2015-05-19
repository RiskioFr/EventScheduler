<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

class WeekInYear implements TemporalExpressionInterface
{
    /**
     * @var int
     */
    protected $weekNumber;

    /**
     * @param int $weekNumber
     */
    public function __construct($weekNumber)
    {
        $filtered = filter_var($weekNumber, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 53],
        ]);
        if (false === $filtered) {
            throw new Exception\InvalidArgumentException(
                'Week must be an integer value between 1 and 53'
            );
        }

        $this->weekNumber = $weekNumber;
    }

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        return $date->format('W') == $this->weekNumber;
    }
}
