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

    public function includes(DateTimeInterface $date) : bool
    {
        $month = MonthValueObject::fromNativeDateTime($date);

        return $this->month->sameValueAs($month);
    }

    public static function january() : self
    {
        return new self(MonthValueObject::JANUARY);
    }

    public static function february() : self
    {
        return new self(MonthValueObject::FEBRUARY);
    }

    public static function march() : self
    {
        return new self(MonthValueObject::MARCH);
    }

    public static function april() : self
    {
        return new self(MonthValueObject::APRIL);
    }

    public static function may() : self
    {
        return new self(MonthValueObject::MAY);
    }

    public static function june() : self
    {
        return new self(MonthValueObject::JUNE);
    }

    public static function july() : self
    {
        return new self(MonthValueObject::JULY);
    }

    public static function august() : self
    {
        return new self(MonthValueObject::AUGUST);
    }

    public static function september() : self
    {
        return new self(MonthValueObject::SEPTEMBER);
    }

    public static function october() : self
    {
        return new self(MonthValueObject::OCTOBER);
    }

    public static function november() : self
    {
        return new self(MonthValueObject::NOVEMBER);
    }

    public static function december() : self
    {
        return new self(MonthValueObject::DECEMBER);
    }
}
