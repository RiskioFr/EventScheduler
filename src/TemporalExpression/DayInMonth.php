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

    public function __construct(int $day)
    {
        $this->day = new MonthDayValueObject($day);
    }

    public function includes(DateTimeInterface $date) : bool
    {
        $day = MonthDayValueObject::fromNativeDateTime($date);

        return $this->day->sameValueAs($day);
    }
}
