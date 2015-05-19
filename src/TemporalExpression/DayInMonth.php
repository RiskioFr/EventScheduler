<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

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
        $filtered = filter_var($dayIndex, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 31],
        ]);
        if (false === $filtered) {
            throw new Exception\InvalidArgumentException(
                'Day must be an integer value between 1 and 31'
            );
        }

        $this->dayIndex = $dayIndex;
    }

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        return $date->format('j') == $this->dayIndex;
    }
}
