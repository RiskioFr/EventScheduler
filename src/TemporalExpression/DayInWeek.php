<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;
use Riskio\EventScheduler\ValueObject\WeekDay as WeekDayValueObject;

class DayInWeek implements TemporalExpressionInterface
{
    /**
     * @var WeekDayValueObject
     */
    protected $day;

    /**
     * @param string|int $day
     */
    public function __construct($day)
    {
        $this->day = WeekDayValueObject::fromNativeOrNumericValue($day);
    }

    public function includes(DateTimeInterface $date) : bool
    {
        $day = WeekDayValueObject::fromNativeDateTime($date);

        return $this->day->sameValueAs($day);
    }

    public static function monday() : self
    {
        return new self(WeekDayValueObject::MONDAY);
    }

    public static function tuesday() : self
    {
        return new self(WeekDayValueObject::TUESDAY);
    }

    public static function wednesday() : self
    {
        return new self(WeekDayValueObject::WEDNESDAY);
    }

    public static function thursday() : self
    {
        return new self(WeekDayValueObject::THURSDAY);
    }

    public static function friday() : self
    {
        return new self(WeekDayValueObject::FRIDAY);
    }

    public static function saturday() : self
    {
        return new self(WeekDayValueObject::SATURDAY);
    }

    public static function sunday() : self
    {
        return new self(WeekDayValueObject::SUNDAY);
    }
}
