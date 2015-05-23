<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;
use Riskio\EventScheduler\ValueObject\Month as MonthValueObject;

class MonthInYear implements TemporalExpressionInterface
{
    /**
     * @var MonthValueObject
     */
    protected $month;

    /**
     * @param string|int $month
     */
    public function __construct($month)
    {
        $this->month = MonthValueObject::fromNativeOrNumericValue($month);
    }

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        $month = MonthValueObject::fromNativeDateTime($date);

        return $this->month->sameValueAs($month);
    }

    /**
     * @return self
     */
    public static function january()
    {
        return new self(MonthValueObject::JANUARY);
    }

    /**
     * @return self
     */
    public static function february()
    {
        return new self(MonthValueObject::FEBRUARY);
    }

    /**
     * @return self
     */
    public static function march()
    {
        return new self(MonthValueObject::MARCH);
    }

    /**
     * @return self
     */
    public static function april()
    {
        return new self(MonthValueObject::APRIL);
    }

    /**
     * @return self
     */
    public static function may()
    {
        return new self(MonthValueObject::MAY);
    }

    /**
     * @return self
     */
    public static function june()
    {
        return new self(MonthValueObject::JUNE);
    }

    /**
     * @return self
     */
    public static function july()
    {
        return new self(MonthValueObject::JULY);
    }

    /**
     * @return self
     */
    public static function august()
    {
        return new self(MonthValueObject::AUGUST);
    }

    /**
     * @return self
     */
    public static function september()
    {
        return new self(MonthValueObject::SEPTEMBER);
    }

    /**
     * @return self
     */
    public static function october()
    {
        return new self(MonthValueObject::OCTOBER);
    }

    /**
     * @return self
     */
    public static function november()
    {
        return new self(MonthValueObject::NOVEMBER);
    }

    /**
     * @return self
     */
    public static function december()
    {
        return new self(MonthValueObject::DECEMBER);
    }
}
