<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;
use Riskio\EventScheduler\ValueObject\MonthDay as MonthDayValueObject;

class DayInMonth implements TemporalExpressionInterface
{
    /**
     * @var MonthDayValueObject
     */
    protected $day;

    /**
     * @param int $day
     */
    public function __construct($day)
    {
        $this->day = new MonthDayValueObject($day);
    }

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        $day = MonthDayValueObject::fromNativeDateTime($date);

        return $this->day->sameValueAs($day);
    }
}
