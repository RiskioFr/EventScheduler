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

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        $day = WeekDayValueObject::fromNativeDateTime($date);

        return $this->day->sameValueAs($day);
    }

    /**
     * @return self
     */
    public static function monday()
    {
        return new self(WeekDayValueObject::MONDAY);
    }

    /**
     * @return self
     */
    public static function tuesday()
    {
        return new self(WeekDayValueObject::TUESDAY);
    }

    /**
     * @return self
     */
    public static function wednesday()
    {
        return new self(WeekDayValueObject::WEDNESDAY);
    }

    /**
     * @return self
     */
    public static function thursday()
    {
        return new self(WeekDayValueObject::THURSDAY);
    }

    /**
     * @return self
     */
    public static function friday()
    {
        return new self(WeekDayValueObject::FRIDAY);
    }

    /**
     * @return self
     */
    public static function saturday()
    {
        return new self(WeekDayValueObject::SATURDAY);
    }

    /**
     * @return self
     */
    public static function sunday()
    {
        return new self(WeekDayValueObject::SUNDAY);
    }
}
